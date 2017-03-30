# Oxwall-GraphQL-API

GraphQL implementation for PHP based Oxwall Social Networking Script

## About GraphQL

RESTful APIs are (going to be) dead. GraphQL, developed by Facebook is intended to be a replacement for REST APIs. 

From the official page of [GraphQL](http://graphql.org/):

> GraphQL is a query language for APIs and a runtime for fulfilling those queries with your existing data. GraphQL provides a complete and understandable description of the data in your API, gives clients the power to ask for exactly what they need and nothing more, makes it easier to evolve APIs over time, and enables powerful developer tools.

## Why GraphQL API for Oxwall?

- If we use REST approach, fetching complicated data objects require multiple requests between the client and server to render single views. For mobile applications operating in variable network conditions, these multiple roundtrips are highly undesirable. GraphQL helps to combine all data requirements in a single request.

- REST endpoints are usually weakly-typed and lack machine-readable metadata. GraphQL provides friendly metadata and easy to understand.

- REST services do not provide options to limit what fields you need in the output. In GraphQL, you define what fields you need and you get only what you need.

- Addition of new fields and business logic will be cumbersome in REST services. You need to maintain multiple versions of API. In GraphQL you can add new fields and logics without impacting existing logic.

Other significant features are:

- Single Client endpoint.

- Simple and composable API: GraphQL Query Language avoids REST endpoints explosion.

- Self-documented: via in-browser IDE GraphiQL.

## Installing Plugin

This is like any other Oxwall plugin and you can upload to *ow_plugins* folder either via ftp or via Cpanel UI or Oxwall Admin page. Once installed you can access the GraphQL endpoint in http://your-site.com/graphql.

## Roadmap

Below is the list of action items. Initially read-only queries will be supported. Later support for mutations will be added.

- [x] Site Metadata
- [x] Users List fetching
- [x] Blog posts fetching
- [ ] Solve [N+1 problem](https://secure.phabricator.com/book/phabcontrib/article/n_plus_one/)
- [ ] Photos
- [ ] User Profile
- [ ] Newsfeed
- [ ] Forum
- [ ] Groups

## Contribution

The plugin uses the excellent [graphql-php](https://github.com/webonyx/graphql-php) library which is the PHP port of GraphQL specification. Contributing to this plugin requires understanding of GraphQL basics and knowledge of using GraphQL-PHP library.

Both are easy to catch-up. Any PR is welcomed. Feel free to create issues for any questions, suggestions and improvements.
