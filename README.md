# Modular Reorder Array Plugin

The **Modular Reorder Array** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It reorders the blueprint definition for modular custom order by making match the array order with the array index keys

The current grav-admin interface (state: early 2017) has no option to reorder modular childpages from within their parent page. Grav has a very cool and smart way to extend and modify existing functionalities through it's extensible blueprint system. By adding a `header.content.order.custom` form field in the template's blueprint, one can almost add this missing behavior.

**BUT**

There seems to be a bug in the grav-admin php `$_POST`->php-object processor which just changes the order of the indexed array and turns it into an associated array with correct position of the array elements, but wrong key indices. If you are interested in details, I recommend to observe the behavior of the resulting frontmatter header with this plugin disabled.

**WHAT DOES THIS PLUGIN DO?**

The plugin subscribes to an event right after the initialization of the grav admin toolchain controller and searches exactly for `header.content.order.custom`. If found, it tidies up the wrong index-keys, respecting the position of the strings within the array. That's it.

**PLEASE NOTE**

PHP is not my main subject as a programmer, nor am I very experienced with Grav. This solution should be considered as temporary as in my opinion it should be a patch for the grav-admin. I didn't make it a patch, as it seems quite tricky to make work in general for all blueprint indexed arrays (without keys).

## Installation

Installing the Modular Reorder Array plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install modular-reorder-array

This will install the Modular Reorder Array plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/modular-reorder-array`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `modular-reorder-array`. You can find these files on [GitHub](https://github.com/quaqua/grav-plugin-modular-reorder-array) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/modular-reorder-array

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/modular-reorder-array/modular-reorder-array.yaml` to `user/config/plugins/modular-reorder-array.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

## Usage

In your modular pages container blueprint, let's call it `themes/<yourtheme>/blueprints/team.yaml`, add a form field for the custom order:
```
title: Team
'@extends':
    type: default

form:
  fields:
    tabs:
      type: tabs
      fields:
        content:
          fields:
            header.content.order.custom:
              label: Modular Ordering
              type: array
              style: vertical
              value_only: true
              default: []
```

New Child pages will be regenerated on every save. Renamed child pages will be
added again on bottom of the list. But their old names will still remain in the
list as well as deleted child pages. As you can see, this is really just a very
rudimentary implementation and urgently needs proper implementation.

## To Do

- [ ] Abandon this plugin and find out a way to implement in grav-admin core.
