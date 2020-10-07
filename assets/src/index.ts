import Rxp from './core/app'

declare global {
  interface Window {
    rxp: typeof Rxp
  }
}

export = Rxp
