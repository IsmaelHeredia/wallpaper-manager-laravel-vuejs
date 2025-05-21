import { useToast } from 'vue-toastification'
import router from '@/router'

const toast = useToast()

type ToastType = 'success' | 'error' | 'info' | 'warning'

interface ShowToastOptions {
  message: string
  type?: ToastType
  timeout?: number
  redirectTo?: string | null
}

export function showToast({
  message,
  type = 'success',
  timeout = 3000,
  redirectTo = null
}: ShowToastOptions): void {
  
  const toastMethod = toast[type]

  toastMethod(message, {
    timeout,
    onClick: () => {
      if (redirectTo) {
        router.push(redirectTo)
      }
    },
    onClose: () => {
      if (redirectTo) {
        router.push(redirectTo)
      }
    }
  })
}
