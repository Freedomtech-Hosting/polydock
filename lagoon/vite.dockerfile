ARG CLI_IMAGE
FROM ${CLI_IMAGE} 

ARG VITE

CMD ["npm", "run", "dev"]
