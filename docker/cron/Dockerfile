FROM alpine:latest

RUN echo "* * * * * echo "Hello world" >> /var/log/cron.log" | crontab -
CMD ["crond","-f"]