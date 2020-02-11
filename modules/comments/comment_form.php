<?php
		$username = $_SESSION['user'];
		$sell = $db->prepare('SELECT * FROM `members` WHERE `name` = :name');
		$sell->execute(array('name' => $username));

		foreach($sell as $row1)
		{
			$avatar = $row1['avatar'];
			if($avatar == "default.gif"){$avatar = "default.png";}

			$nickname = $row1['name'];

			echo'<div id="comment-form">
                <h4 class="text-uppercase">Adauga un comentariu - '.$nickname.'</h4>
                <form>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="comment">Comment <span class="required text-primary">*</span></label>
                        <textarea name="comentariu" rows="4" class="form-control"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 text-right">
                      <button class="btn btn-template-outlined" type="submit" name="addcomm"><i class="fa fa-comment-o"></i> Adauga</button>
                    </div>
                  </div>
                </form>
              </div><hr>';
		}
?>