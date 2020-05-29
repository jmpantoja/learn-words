export CONTAINER_REGISTRY_BASE=planb/learn_words

export TF_VAR_APP_ENV=dev
export TF_VAR_DIGITALOCEAN_TOKEN=c8bb043d6e5181676a3ecb0ad77d40adb4b54b1bc634a887326580066bc4679e

export TF_VAR_GITHUB_PASSWORD=pato@tierra#3
export TF_VAR_GITHUB_USER=jmpantoja
export TF_VAR_GITHUB_REPOSITORY_URL=https://github.com/jmpantoja/britannia.git
export TF_VAR_GITHUB_BRANCH=develop

export TF_VAR_APP_URL=britannia.ovh
export TF_VAR_APP_ENV=dev
export TF_VAR_APP_SECRET=eZCmRupDXdcD

export TF_VAR_MYSQL_ROOT_PASSWORD=Vq4q9HzEJ7VrHuZq
export TF_VAR_MYSQL_DATABASE=britannia
export TF_VAR_MYSQL_USER=britannia
export TF_VAR_MYSQL_PASSWORD=FZf5PgHt37mW2n5a

export TF_VAR_APP_DESCOM_USER=BRITANNIA
export TF_VAR_APP_DESCOM_PASSWORD=abrita11julio

export TF_VAR_APP_MAILER_MANAGERS="smtp://britannia.elpuerto.web%40gmail.com:J4yIims5Ws@smtp.googlemail.com:465/?timeout=60&encryption=ssl&auth_mode=login"
export TF_VAR_APP_MAILER_RECEPTION="smtp://britannia.elpuerto.web%40gmail.com:J4yIims5Ws@smtp.googlemail.com:465/?timeout=60&encryption=ssl&auth_mode=login"
export TF_VAR_APP_MAILER_TEACHERS="smtp://britannia.elpuerto.web%40gmail.com:J4yIims5Ws@smtp.googlemail.com:465/?timeout=60&encryption=ssl&auth_mode=login"

export DOCKER_PROXY_DOMAINS="www.academiabritannia.es -> http://api:80 \#production"
export DOCKER_PROXY_DOMAINS="www.learn-words.local -> http://cache-proxy:80 \#local"
