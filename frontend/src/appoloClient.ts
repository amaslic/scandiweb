import { ApolloClient, InMemoryCache } from "@apollo/client";

const isLocalhost = window.location.hostname === "localhost";

const client = new ApolloClient({
  uri: isLocalhost ? "/graphql" : import.meta.env.VITE_GRAPHQL_URL,
  cache: new InMemoryCache(),
  credentials: "include",
  defaultOptions: {
    watchQuery: {
      fetchPolicy: "no-cache",
      errorPolicy: "ignore",
    },
    query: {
      fetchPolicy: "no-cache",
      errorPolicy: "all",
    },
    mutate: {
      fetchPolicy: "no-cache",
    },
  },
});

export default client;
