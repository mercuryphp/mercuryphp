connectionStrings:
    db:
        host:127.0.0.1
        dbname:test
        pwd:yellow
        uid:root
        charset:utf8
        user: 
            uid: root
            pass: yell
        ff:test
    db3:
        host:db3.com
        userLangs: ["ENG", "FR", "GR"]
session:
    name: new_sess
    http: true
    expire: 120
    secure: false
include:
    -resources: parametersx.yml
    