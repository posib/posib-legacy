# posib. (legacy)

> Old PHP implementation of **posib.**, a simple in-place editing CMS.

* * *

The two firsts versions of **posib.** was in PHP, running well but closed source. With the goal to rewrite **posib.** in node.js as an educational purpose, the old PHP version of **posib.** will now be open sourced.

Note that, even if the current code are running in production for many sites, it will never be documented more than this file. Feel free to test it if you want, fork or rewrite any part of it. I'm _out_ of the PHP world for so long that I can't, really, assure the maintenance of a PHP version.

The only purpose of this code is to serve as _proof of concept_, demo and base of the **posib.** CMS before rewriting it, in `node.js` with my students.

## Run project

### with docker

* `docker build -t posib/posib-legacy .`
* `docker run -p 80:80 posib/posib-legacy`

## Documentation

**posib.** is a simple _in-place editing_ CMS: from the base of simple static templates, **posib.** implements the logic and allow users to edit the content. It shorten development since the only work to do is to write templates and styles.

**posib.** uses `data` attributes to target the editable elements: each element with a `data-brick` element is editable by **posib.**, via the admin interface located at `/admin/`.

### Filesystem organisation

All the `html` files at the root of the repository are the templates of the site. Static files & styles are stored inside the `css/` folder. The `contents/` folder contains the user-uploaded medias (photos, files, etc) and the `data.json` file, which contain the contents of the editable elements.  
The `posib` folder contain the source of the **posib.** CMS. An `.htaccess` file redirects all the requests to the `posib.php` file, which is the main entry point to the sources.

This version of **posib.** has a pseudo version-managment: each successive state of an element is saved, allowing user to revert some elements to a previous state.
