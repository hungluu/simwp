<?php
namespace Simwp\Form;
use Simwp;

class Textarea extends Input {
	public function render($key, $extra = ''){
		?>
		<div class="textarea-wrap">
			<!--<label class="prompt" for="content" id="content-prompt-text">Bạn đang nghĩ gì?</label>-->
			<textarea class="simwp-textarea" name="<?= $key ?>" rows="3" cols="15" autocomplete="off"><?= Simwp::get($key) ?></textarea>
		</div>
		<?php
	}
}
