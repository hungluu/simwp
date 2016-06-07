**0.0.6-alpha**
- Add Form\\Editor component to make TinyMCE field
- Add more javascript events
- Component\\Section now contains Component\\Option, so do all other components
- Add Component\\Level
- Some fixes

**0.0.5-alpha**
- Refine Form Loop-type components
- Move handling code for removable notices into admin_init hook
- Fix wrong naming in section and page hooks
- Add a new section rendering mode that uses both navbar and custom content

**0.0.4-alpha**
- Use array() instead of [] to support PHP 5.3
- Add a basic javascript api for extending components
- Simwp\\Form\\Loop now does not require $options, will get the option_value if $options not provided
- ...

**0.0.3-alpha**
- Add a changelog
- Add TODO
- Now change method Simwp::managed to Simwp::handled for checking an option is sanitized, valid with its validators and accessible for current section, add a shortcut to Simwp\\Component\\Option
- Not including compiled autoload files and vendor folder anymore, the vendor folders now moved to another repository named 'simwp-dist' for non-composer users
- Add Simwp::is shortcut to some is- prefixed methods, will work for simple checks like is('admin'), is('user'), is('dashboard')
- Add Simwp::make factory method to make components
- Add Simwp::can and Simwp::cant to detect user capabilities
