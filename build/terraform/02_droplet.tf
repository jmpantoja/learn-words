#
# Creamos el droplet
variable "GITHUB_USER" {}
variable "GITHUB_PASSWORD" {}
variable "GITHUB_REPOSITORY_URL" {}
variable "GITHUB_BRANCH" {}
variable "APP_ENV" {}
variable "APP_NAME" {}
variable "APP_DOMAIN" {}


data "template_file" "init" {
template = "${file("userdata.yaml")}"
vars = {
    github_repository_url = "${var.GITHUB_REPOSITORY_URL}"
    github_branch = "${var.GITHUB_BRANCH}"
    github_user = "${var.GITHUB_USER}"
    github_password = "${var.GITHUB_PASSWORD}"
    app_env = "${var.APP_ENV}"
    directory = "/${var.APP_NAME}"
  }
}

resource "digitalocean_droplet" "web" {
  image     = "ubuntu-18-04-x64"
  name      = "${var.APP_NAME}"
  region    = "lon1"
  size      = "s-1vcpu-1gb"
  user_data = "${data.template_file.init.rendered}"
  ssh_keys  = ["${digitalocean_ssh_key.main.fingerprint}"]

  provisioner "remote-exec" {
    inline = [
      "mkdir -p /${digitalocean_droplet.web.name}/vars",
    ]
  }

  provisioner "file" {
    source      = "../vars/"
    destination = "/${digitalocean_droplet.web.name}/vars"
  }
}
