ARG CLI_IMAGE
FROM ${CLI_IMAGE} 

# Replace the php.ini
COPY lagoon/php-worker.ini /usr/local/etc/php/php.ini

# Install Supervisord
RUN apk add --update supervisor && rm  -rf /tmp/* /var/cache/apk/*
RUN mkdir -p /etc/supervisor/conf.d/ && fix-permissions /etc/supervisor/conf.d
ADD lagoon/supervisord.conf /etc/
ADD lagoon/supervisord-worker.conf /etc/supervisor/conf.d/
ADD lagoon/supervisord-schedule.conf /etc/supervisor/conf.d/

# Run Supervisor
CMD ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
