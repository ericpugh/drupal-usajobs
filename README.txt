
Contents of this file
---------------------

 * Overview
 * Installation
 * Setup Tips
 * Overriding the Block Template


Overview
--------
This module creates a block which displays a job listings from USAjobs.gov
The module provides a configuration form which allows a user with permission
to set parameters used to query the USAjobs Search API. Results of this query
are output in a custom "USAjobs" block, and also as JSON at
 example.com/usajobs/api/list
for sites wish to manipulate the results using Javascript.
More information about the USAjobs Search API:
https://developer.usajobs.gov/Search-API/Instantiating-the-API

Installation
-----------

1. Place the usajobs-search-api directory in your modules directory.
2. Enable the usajobs_integration module at admin/modules.
3. Configure it at admin/config/usajobs_integration


Setup Tips
------------

1. Place the USAjobs Block in a block region (admin/structure/block) to display
on your site.
2. override template/block--usajobs-search-api-block.html.twig in your theme to customize
 block output


Overriding the Block Template
-----------------------------
To override the block template you have to create a template in your theme
and add the job listings object to the $variables

Example template in mytheme/templates/block--usajobs-block.html

{% extends "@usajobs/block--usajobs-search-api-block.html.twig" %}
{% block content %}
    <ol>
    {% for job in jobs %}
        <li>item: {{ loop.index }} {{ job.positionTitle }}</li>
    {% endfor %}
    </ol>
{% endblock %}


Example hook_preprocess_block() in mytheme.theme

function mytheme_preprocess_block(&$variables) {
  if ($variables['plugin_id'] == 'usajobs_integration_block'){
    if( Drupal::moduleHandler()->moduleExists('usajobs_integration') ){
      $variables['jobs'] = Drupal\usajobs_integration\JobListingCollection::getJobListings();
    }
  }
}
