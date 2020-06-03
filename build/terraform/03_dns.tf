# Creamos un dominio nuevo

resource "digitalocean_domain" "main" {
  name = "${var.APP_DOMAIN}"
}

# Add a record to the domain
resource "digitalocean_record" "www" {
  domain = "${digitalocean_domain.main.name}"
  type   = "A"
  name   = "www"
  ttl    = "40"
  value  = "${digitalocean_droplet.web.ipv4_address}"
}

resource "digitalocean_record" "root" {
  domain = "${digitalocean_domain.main.name}"
  type   = "A"
  name   = "@"
  ttl    = "40"
  value  = "${digitalocean_droplet.web.ipv4_address}"
}
