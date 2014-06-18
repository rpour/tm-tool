sudo add-apt-repository ppa:chris-lea/node.js
sudo apt-get update
sudo apt-get install nodejs php-pear npm

# remove a conflicting install of npm that I had tried earlier
sudo apt-get update && sudo apt-get -y dist-upgrade
sudo npm install -g grunt grunt-cli grunt-contrib-clean grunt-replace grunt-contrib-concat grunt-contrib-watch grunt-contrib-jasmine grunt-contrib-connect grunt-saucelabs grunt-gitinfo

sudo pear install PHP_CodeSniffer