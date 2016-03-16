
Contents of this file
---------------------

 * Overview
 * Installation
 * Setup Tips


Overview
--------
This module creates a block which displays a job listings from USAjobs.gov
The module provides a configuration form which allows a user with permission
to set parameters used to query the USAjobs Search API. Results of this query
are output in a custom "USAjobs" block, and also as JSON at example.com/usajobs/api/list
for sites wish to manipulate the results using Javascript.
More information about the USAjobs Search API:
https://developer.usajobs.gov/Search-API/Instantiating-the-API

Installation
-----------

1. Place the usajobs directory in your modules directory.
2. Enable the usajobs module at admin/modules.
3. Configure it at admin/config/usajobs


Setup Tips
------------

1. Place the USAjobs in a block region (admin/structure/block) to display on your site.
2. override template/block--usajobs-block.html.twig in your theme to customize block output
