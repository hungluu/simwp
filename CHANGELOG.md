**0.0.3-alpha**
- Add a changelog
- Add TODO
- Now change method Simwp::managed to Simwp::handled for checking an option is sanitized, valid with its validators and accessible for current section, add a shortcut to Simwp\\Component\\Option
- Not including compiled autoload files and vendor folder anymore, the vendor folders now moved to another repository named 'simwp-dist' for non-composer users
- Add Simwp::is shortcut to some is- prefixed methods, will work for simple checks like is('admin'), is('user'), is('dashboard')
- Add Simwp::make factory method to make components
- Add Simwp::can and Simwp::cant to detect user capabilities
