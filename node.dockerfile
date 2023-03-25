FROM  node:alpine

WORKDIR /app

COPY src/client /app

RUN yarn global add @vue/cli

EXPOSE 5000
