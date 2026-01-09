FROM node:25-trixie-slim

ENV LANG en_US.UTF-8

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    chromium

RUN groupadd -r user && useradd -rm -g user -G audio,video user

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /home/user/app

# COPY ../crawler/package*.json ./

# RUN chown -R user:user /home/user/app

# USER user

# RUN npm install

# COPY --chown=user:user . .

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true 
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

CMD ["npm", "run", "dev"]