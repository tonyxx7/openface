#!/bin/bash
#
#  This program is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#  appGeneration.sh
#

appName=
dependantApp=
dbUser=
dbPassword=
dbName=
apiKey=
apiSecret=
appCanvasPath=
appCallBack=

#
# create empty application
#
cd $HOME/workspace
rails -d mysql $appName
cd $appName
#
# configure database
#
sed -i -e"s/username: root/username: ${dbUser}/" \
-e"s/password:/password: ${dbPassword}/" \
-e"s/database: ${appName}_development/database: ${dbName}/" \
config/database.yml

#
# generate models
#
tableList=`grep 'CREATE TABLE' $HOME/workspace/${dependantApp}/install/createDB.sql|sed -e"s/ (//" -e"s/.* //" |sort|uniq`

for table in $tableList
do
  echo 'generate model' $table
  ruby script/generate model $table
done

#
#install rfacebook
#
script/plugin install svn://rubyforge.org/var/svn/rfacebook/trunk/rfacebook/plugins/rfacebook
rake facebook:setup
#
# configure rfacebook
#
sed -i -e"s/YOUR_API_KEY_HERE/${apiKey}/" \
-e"s/YOUR_API_SECRET_HERE/${apiSecret}/" \
-e"s'/yourAppName/'${appCanvasPath}'" \
-e"s'/path/to/your/callback/'${appCallBack}'" \
config/facebook.yml
