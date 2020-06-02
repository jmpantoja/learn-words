#!/usr/bin/python
import jinja2
import json
import yaml
import sys
import os

args = sys.argv
if len(args) != 3:
    raise Exception('Este script necesita dos parametros <env> <template>')

dirname = os.path.dirname(__file__)

vars_path = '{:s}/vars/{:s}.yaml'.format(dirname, args[1])
template_path = '{:s}/tpl/{:s}'.format(dirname, args[2])
template_path = args[2]

sites_path = '{:s}/vars/sites.yaml'.format(dirname)
sites = yaml.full_load(open(sites_path, 'rb').read())

vars = yaml.full_load(open(vars_path, 'rb').read())
vars['sites'] = sites.items()

templateLoader = jinja2.FileSystemLoader(searchpath="./build/tpl")
templateEnv = jinja2.Environment(loader=templateLoader)

template = templateEnv.get_template(template_path)

#template = jinja2.Template(open(template_path).read())

print(template.render(vars))
