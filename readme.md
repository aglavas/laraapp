## Instructions

- clone the projects
- run install script - ./install.sh. You may need to turn off local server and local mysql server, since this docker image will use default ports.
- install script will build the project, run migrations, seeders, run tests and run code sniffer (PSR-2)
- to access project use: http:/localhost

## Notes

-   project handles cases exactly like in the instructions. With that being said important note is that products support only one instance of specific attribute.
    For example if we have attribute "Color", each product can have only one attribute "Color". Connected to this - when searching products using attributes,
    it is not possible to use AND operator with attributes, OR operator is supported.
