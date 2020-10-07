// import { AnyAction, CombinedState, createStore, Store, Unsubscribe } from 'redux'
// import pluginState from '../plugin/state'
// import pluginRequest from '../plugin/request'
import pluginGraphql from '../plugin/graphql';

interface initConfig {
  csrf_token: string
  request_uri: string
}

export interface pluginInterface {
  name: string
  install(App: typeof rxp, config: any): void
}

export class Rxp {
  private _installedPlugins = new WeakSet();
  private booted: boolean = false
  public config: any = {} // FIXME
  public lib: any = {} // FIXME

  constructor() {
  }

  boot(init: initConfig) {
    if (this.booted) {
      return this
    }

    this.booted = true
    this.config = init

    // FIXME
    // this.use(pluginState)
    // this.use(pluginRequest)
    this.use(pluginGraphql)
  }

  use(plugin: pluginInterface): Rxp {
    if (this._installedPlugins.has(plugin)) {
      return this
    }

    const args = [this, plugin]

    plugin.install.apply(plugin, args)

    this._installedPlugins.add(plugin)

    return this
  }
}

declare let rxp: Rxp
export default rxp = new Rxp()
