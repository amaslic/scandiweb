import { ApolloClient, InMemoryCache } from "@apollo/client";

const client = new ApolloClient({
  //uri: import.meta.env.VITE_API_URL,
  uri: '/graphql',
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
