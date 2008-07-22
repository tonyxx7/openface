# Copyright (C) 2008 App Tsunami, Inc.
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
# tsunamiframewrk_controller.rb

class TsunamiframewrkController < ApplicationController
  before_filter :require_facebook_login

  def finish_facebook_login
    redirect_to :action => "index"
  end

  def index
    @user = fbsession.users_getInfo(
      :uids => fbsession.session_user_id,
      :fields => ["first_name","last_name", "pic_square", "status"] )
  end

  def debug
# RFacebook offers a built-in debug panel
    render_with_facebook_debug_panel
  end
end
