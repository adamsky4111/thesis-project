FROM node:lts-alpine

RUN npm install -g http-server

WORKDIR /usr/src/apps/frontend

COPY --chown=1000:1000 apps/frontend /usr/src/apps/frontend

RUN npm install

RUN npm run build

CMD [ "http-server", "dist" ]
