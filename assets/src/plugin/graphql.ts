import { pluginInterface } from '../core/app'
import { ApolloClient } from 'apollo-client'
import { createHttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'
import { IntrospectionFragmentMatcher } from 'apollo-cache-inmemory';
import introspectionQueryResultData from '../introspection-result';

const pluginGraphql: pluginInterface = {
  name: 'graphql',
  install(App) {
    App.lib.apolloClient = new ApolloClient({
      link: createHttpLink({
        uri: App.config.request_uri + 'querykit-graphql',
        headers: {
          'X-CSRF-TOKEN': App.config.csrf_token
        }
      }),
      cache: new InMemoryCache({
        fragmentMatcher: new IntrospectionFragmentMatcher({ introspectionQueryResultData })
      })
    })
  }
}

export default pluginGraphql
