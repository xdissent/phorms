Phorms
======

Introduction
------------

Phorms is a general purpose HTML form factory for PHP. Its goal is to 
provide a simple way to create easily extensible forms. Its usage is 
vaguely similar to Django's forms library.

At the moment, documentation is a little sparse, but the API docs are 
complete and there are two examples showing the most common usage included 
in the package.

There is another, more actively committed, repository on github at 
http://github.com/xdissent/phorms. Updates there will eventually find their 
way here.


Documentation
~~~~~~~~~~~~~

Documentation for the latest stable release of Phorms is always located at
`http://static.artfulcode.net/phorms/doc/ 
<http://static.artfulcode.net/phorms/doc/>`_. The current development version
contains more recent documentation, but must be built using the `phpdoc` 
command.


The ``pear-style-guide`` Branch
-------------------------------

There exists a ``pear-style-guide`` branch 
`on GitHub <http://github.com/xdissent/phorms/tree/pear-style-guide>`_.
It contains a dramatic reorganization of Phorms that may or may not ever
make into a release. The branch strictly conforms to the 
`PEAR style guide <http://pear.php.net/manual/en/standards.php>`_ and might
contain a few more recent bug fixes that the ``master`` 
`branch <http://github.com/xdissent/phorms>`_. For now, this branch is 
considered experimental and should not yet be relied upon.
See below for the rational behind the changes and to get an idea of where
where this branch is going.


The Future
----------

*(according to Greg Thornton)*


Preface
~~~~~~~

Below are some notes about what I want in a PHP forms library. I'd love to 
see these ideas applied to Phorms, but who knows what will happen. A fork
from Phorms might be inevitable too since some of my proposed changes are 
rather invasive and idealistic (read: *naive*). So this is mostly just 
outlining a forms Utopia of my imagination, which may never even be realized.


How We Got Here
~~~~~~~~~~~~~~~

As a serious Django guy, I was initially drawn to this package because of its 
similarity in function to django.forms. I knew that Phorms and I would have a 
long happy relationship, so I decided to ensure the source got a good tidying 
up to promote better adoption and further development from a larger user base.  
As a *former* PHP love/hater, I knew that the maintenance required of something 
like Phorms could divorce it from my own software sooner rather than later. So, 
I reviewed my options to choose how to proceed in minimizing the effort required
to keep Phorms clean and easy going. Remembering the countless nightmares I've 
had trying to handle include paths, class inheritance access rules, naming 
conventions, and code documentation in large and small PHP projects alike, I 
put some real thought into how to achieve the zen of Python I've grown to expect
from a software project.


Namespacing
~~~~~~~~~~~

The first problem to tackle was namespacing. Until fairly recently, PHP has 
always had a single global namespace in which all classes are defined. The 
obvious problem arises when someone wants to use the class name `Field` but he
also wants to use our forms package, which probably will define a `Field` class
as well. Now we start to think about calling our class something like 
`PhormField` to prevent the name collision, but that's not as intuitive or 
portable. It doesn't really begin to be a huge risk until the project becomes 
larger with many internal class names, but the point is that there is *always* a
chance of collision if you have a global namespace. Luckily, PHP 5.3.0 includes
support for `namespacing <http://php.net/manual/en/language.namespaces.php>`_, 
and this is what we should ideally be using. Unfortunately, because it's a 
*huge* untertaking to convert a package to it's own namespace and since I don't 
know every internal detail of Phorms yet, I really wouldn't be comfortable 
doing it myself at this point. Plus it would  commit Phorms to PHP versions 
5.3.0 and higher only, which is a dealbreaker for most large shared hosting 
situations (MediaTemple for example [1]_). So we're stuck with prefixes as 
pseudo-namespaces, but what naming convention should we choose? Well, it 
doesn't matter all that much as long as we're consistent, but we really should 
be trying to be as compatible as possible with whatever we consider to be
a de-facto standard. In my mind, that's PEAR, and luckily they have a coding 
standard that gives us some pretty good guidelines to follow about namespacing.
It is suggested that class names be defined as `Package_Subpackage_ClassName`, 
where the `Subpackage` element is optional. The downside is that names can get 
to be pretty long, but they're extremely clear and dramatically reduce the risk
of a name collision. The terms "package" and "subpackage" may also refer to the
phpDoc package and subpackage, which is a bonus because we use that for our 
documentation anyway. The other possible standard I'd choose over PEAR is 
`Zend's <http://framework.zend.com/manual/en/coding-standard.coding-style.html>`_, 
and we may well switch to it in the future. Both share enough 
conventions to make it feasible if not reasonable to do so if Zend is 
determined to be superior.


Importing Class Definitions
~~~~~~~~~~~~~~~~~~~~~~~~~~~

PHP's mechanism for including source files is notoriously crappy and shouldn't 
be trusted. [2]_


Documentation
~~~~~~~~~~~~~

We use phpDoc everywhere.


Testing
-------

We use SimpleTest for testing.

Obsessive Compulsions
~~~~~~~~~~~~~~~~~~~~~

There are still a couple of things that irk me about Phorms, and PHP in 
general. For one, I rely on Python's keyword arguments heavily and lament
the absence of such a feature in PHP. I've run across a few attempts to 
simulate named arguments [3]_ [4]_, but they are often confusing, repetitive or
impossible to document easily. I would not be hesitant to refactor the 
entire package if a reasonable solution was found, but that seems like a
long shot.


Wishlist
~~~~~~~~

Here are some tasks to consider completing down the road. Some may be 
deemed irrelevant or even impossible in the future.

* Refactor validation:

  * Add `Empty Value` as argument like Django.

  * Add `required` as argument to the field like Django.
 
  * Review passing validators to fields in Django to see how (or if) we should 
    do that.

* Add formset support like Django.

* Make current fields operate more like their Django kin:

  * Password fields should not have a hash function.
  
  * Decimalfield should have a minimum value and maximum value.
  
* Add Paver scripts:

  * Development environment setup.
  
  * Test runner.
  
  * Code style checker.
  
  * Release package generator.
  
  * Documentation builder.


Notes
~~~~~

.. [1] Mediatemple runs PHP 5.2.6 and 4.4.8.

.. [2] Autoloading is available in PHP 5.1.2.

.. [3] `Faking named parameters in PHP <http://www.marco.org/59195010>`_

.. [4] `PHP: func_get_args - Manual <http://php.net/manual/en/function.func-get-args.php>`_