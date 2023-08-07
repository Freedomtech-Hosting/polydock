ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM amazeeio/nginx
COPY lagoon/nginx.conf /etc/nginx/conf.d/app.conf

COPY --from=cli /app /app

ENV WEBROOT=public
