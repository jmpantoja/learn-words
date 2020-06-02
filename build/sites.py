#!/usr/bin/python
import jinja2
import json
import yaml
import sys
import os



args = sys.argv
if len(args) != 3:
    raise Exception('Este script necesita dos parametros <env> <target>')

dirname = os.path.dirname(__file__)

template_path = '{:s}/tpl/site.conf'.format(dirname)
template = jinja2.Template(open(template_path).read())

env = args[1]

vars_path = '{:s}/vars/{:s}.yaml'.format(dirname, env)
vars = yaml.full_load(open(vars_path, 'rb').read())

sites_path = '{:s}/vars/sites.yaml'.format(dirname)
sites = yaml.full_load(open(sites_path, 'rb').read())

host = vars['APP_DOMAIN']

target_dir = os.path.abspath(args[2])

for site, data in sites.items():
    target = '{:s}/{:s}.conf'.format(target_dir, site)
    data['host']= host

    content = template.render(data)

    file = open(target, "w")
    file.write(content)
    file.close()

