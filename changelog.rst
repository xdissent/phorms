pear-style-guide
----------------

* Accurate and consistent documentation has been added throughout.

* All classes renamed to be pseudo-namespaced by package and subpackage names.

* All code passes PHP_CodeSniffer tests for PEAR compliance.

* SimpleTest was added to package.

* All classes have a SimpleTest test of some kind.

* Help text added as (usually) required argument to the field constructor.

* Most privates became protected (these will all be reviewed later).

* `BooleanField` can now be required and passed an initial value of true.
  See `issue #2 <http://github.com/xdissent/phorms/issues/closed/#issue/2>`_.

* `URLField` can now accept an empty value without throwing a validation error.
  See `issue #3 <http://github.com/xdissent/phorms/issues/closed/#issue/3>`_.

* `ValidationError` renamed `Phorms_Validation_Error`.
 
* Some fields renamed to match Django's naming convention:
 
  * `TextField` renamed `Phorms_Fields_CharField`.
 
  * `LargeTextField` renamed `Phorms_Fields_TextField`.
 
  * `DropDownField` renamed `Phorms_Fields_ChoiceField`.

* Some fields are not yet implemented:

  * `FileField`
  
  * `ImageField`

  * `DateTimeField`

  * `RegexField`

  * `ScanField`

  * `OptionsField`

* Added initial support for fieldsets.
 

1.02
----

* Fixed bug in Phorm where default data may sometimes be overwritten incorrectly.

* Fixed bug in HiddenField where parent class was instantiated incorrectly.


1.01
----

* Fixed bug in PhormField::is_valid where false values skipped field-level validation.

* FileFields and ImageFields now pass either null or a File/Image instance to user-defined validators.

* Added file_drop.php to examples.


1.0
---

* Initial release.