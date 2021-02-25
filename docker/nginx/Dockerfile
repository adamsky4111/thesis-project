FROM nginx:1.19.0-alpine

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]
