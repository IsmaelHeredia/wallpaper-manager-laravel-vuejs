package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"log"
	"math/rand"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"strings"
	"time"
)

type RespuestaLogin struct {
	Token string `json:"access_token"`
}

type Fondo struct {
	ID         int      `json:"id"`
	Nombre     string   `json:"nombre"`
	Imagen     string   `json:"imagen"`
	Estaciones []Nombre `json:"estaciones"`
	Horarios   []Nombre `json:"horarios"`
}

type Nombre struct {
	Nombre string `json:"nombre"`
}

var (
	fondoActual string
	urlBase     = "http://127.0.0.1:9090"
)

func iniciarSesion() (string, error) {
	url := urlBase + "/api/v1/ingreso"
	datos := map[string]string{
		"email":    "admin@localhost.com",
		"password": "admin",
	}
	res, _ := json.Marshal(datos)
	resp, err := http.Post(url, "application/json", bytes.NewBuffer(res))
	if err != nil {
		return "", err
	}
	defer resp.Body.Close()

	var respuesta RespuestaLogin
	err = json.NewDecoder(resp.Body).Decode(&respuesta)
	return respuesta.Token, err
}

func obtenerFondos(token string) ([]Fondo, error) {
	req, _ := http.NewRequest("GET", urlBase+"/api/v1/wallpapers", nil)
	req.Header.Add("Authorization", "Bearer "+token)

	resp, err := (&http.Client{}).Do(req)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()

	var fondos []Fondo
	err = json.NewDecoder(resp.Body).Decode(&fondos)
	return fondos, err
}

func obtenerEstacionYMomento() (string, string) {
	now := time.Now().In(time.FixedZone("ART", -3*60*60))

	var estacion string
	switch now.Month() {
	case 12, 1, 2:
		estacion = "Verano"
	case 3, 4, 5:
		estacion = "Otoño"
	case 6, 7, 8:
		estacion = "Invierno"
	default:
		estacion = "Primavera"
	}

	hora := now.Hour()
	switch {
	case hora >= 6 && hora < 12:
		return estacion, "Mañana"
	case hora >= 12 && hora < 18:
		return estacion, "Tarde"
	case hora >= 18 && hora < 21:
		return estacion, "Noche"
	default:
		return estacion, "Madrugada"
	}
}

func filtrarFondos(fondos []Fondo, estacion, momento string) []Fondo {
	var filtrados []Fondo
	for _, f := range fondos {
		if contiene(f.Estaciones, estacion) && contiene(f.Horarios, momento) {
			filtrados = append(filtrados, f)
		}
	}
	return filtrados
}

func contiene(lista []Nombre, valor string) bool {
	for _, n := range lista {
		if strings.EqualFold(n.Nombre, valor) {
			return true
		}
	}
	return false
}

func limpiarDescargas(actual string) {
	archivos, _ := os.ReadDir("downloads")
	for _, f := range archivos {
		if f.Name() != actual {
			_ = os.Remove(filepath.Join("downloads", f.Name()))
		}
	}
}

func descargarFondo(nombre string) (string, error) {
	resp, err := http.Get(urlBase + "/storage/wallpapers/" + nombre)
	if err != nil {
		return "", err
	}
	defer resp.Body.Close()

	os.MkdirAll("downloads", 0o755)
	ruta := filepath.Join("downloads", nombre)
	limpiarDescargas(nombre)

	out, err := os.Create(ruta)
	if err != nil {
		return "", err
	}
	defer out.Close()

	_, err = io.Copy(out, resp.Body)
	return ruta, err
}

func runCommand(cmd *exec.Cmd) error {
	var stdout, stderr bytes.Buffer
	cmd.Stdout, cmd.Stderr = &stdout, &stderr
	if err := cmd.Run(); err != nil {
		log.Printf("Falló el comadno : %v\nstderr: %s\n", err, stderr.String())
		return err
	}
	return nil
}

func establecerFondo(ruta string) error {
	switch {
	case esLinux():
		abs, _ := filepath.Abs(ruta)
		uri := "file://" + abs
		cmd := exec.Command("gsettings", "set", "org.gnome.desktop.background", "picture-uri", uri)
		cmd.Env = append(os.Environ(),
			"DISPLAY=:0",
			"DBUS_SESSION_BUS_ADDRESS=unix:path=/run/user/1000/bus",
		)
		return runCommand(cmd)

	case esWindows():
		abs, _ := filepath.Abs(ruta)
		ps := `Add-Type -TypeDefinition "using System.Runtime.InteropServices;
public class Wallpaper{[DllImport(\"user32.dll\",SetLastError=true)]
public static extern bool SystemParametersInfo(int uAction,int uParam,string lpvParam,int fuWinIni);}";` +
			`[Wallpaper]::SystemParametersInfo(20,0,"` + abs + `",3)`
		return runCommand(exec.Command("powershell", "-command", ps))

	default:
		return fmt.Errorf("No se reconocio el sistema")
	}
}

func esLinux() bool   { return existeArchivo("/etc/os-release") }
func esWindows() bool { return strings.Contains(strings.ToLower(os.Getenv("OS")), "windows") }

func existeArchivo(path string) bool { _, err := os.Stat(path); return err == nil }

func main() {

	log.SetFlags(log.LstdFlags | log.Lshortfile)
	log.SetOutput(os.Stdout)

	log.Println("Iniciando sesión ...")

	token, err := iniciarSesion()

	if err != nil {
		log.Fatal("Fallo al iniciar la sesión : ", err)
	}

	log.Println("Descargando fondos ...")

	fondos, err := obtenerFondos(token)

	if err != nil {
		log.Fatal("No se pudieron obtener los fondos : ", err)
	}

	os.MkdirAll("downloads", 0o755)
	limpiarDescargas("")

	for {
		estacion, momento := obtenerEstacionYMomento()
		log.Printf("Estación : %s | Momento del día : %s\n", estacion, momento)

		posiblesWalls := filtrarFondos(fondos, estacion, momento)
		if len(posiblesWalls) == 0 {
			log.Println("No se encontraron fondos con esos filtros")
			time.Sleep(3600 * time.Second)
			continue
		}

		fondo := posiblesWalls[rand.Intn(len(posiblesWalls))]

		if fondo.Imagen != fondoActual {
			log.Println("Descargando : ", fondo.Imagen)
			ruta, err := descargarFondo(fondo.Imagen)
			if err != nil {
				log.Println("Error al descargar : ", err)
			} else if err = establecerFondo(ruta); err == nil {
				log.Println("Fondo cambiado : ", fondo.Nombre)
				fondoActual = fondo.Imagen
			}
		}

		time.Sleep(3600 * time.Second)
	}
}
