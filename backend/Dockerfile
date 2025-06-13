FROM node:23

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .

RUN npm run build
COPY templates dist/templates/
COPY assets dist/assets/


EXPOSE 3000
CMD ["node", "dist/main.js"]