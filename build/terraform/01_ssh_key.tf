#
# Exportamos nuestra key SSH

resource "digitalocean_ssh_key" "main" {
  name       = "${var.APP_NAME}"
  public_key = "${file("id_rsa.pub")}"
}

