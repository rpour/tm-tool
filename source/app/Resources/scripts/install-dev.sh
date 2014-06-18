sudo add-apt-repository ppa:chris-lea/node.js
sudo apt-get update
sudo apt-get install nodejs npm

# remove a conflicting install of npm that I had tried earlier
# sudo apt-get update && sudo apt-get -y dist-upgrade
# sudo npm install -g grunt grunt-cli grunt-contrib-clean grunt-replace grunt-contrib-concat grunt-contrib-watch grunt-contrib-jasmine grunt-contrib-connect grunt-saucelabs grunt-gitinfo

sudo npm install -g grunt-cli


sudo apt-get install ruby-full rubygems1.8
sudo gem install sass
sudo gem install compass

sudo apt-get install php-pear
sudo pear install PHP_CodeSniffer