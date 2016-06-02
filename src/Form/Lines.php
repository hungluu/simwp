<?php
namespace Simwp\Form;

class Lines extends Loop {
	public function before($key, $options){
		echo '<table id="simwp-input-' . $key . '" class="simwp-input-lines"><tbody>';
	}
	public function after($key, $options){
		echo '<tr>
				<td>
					<input type="text" class="simwp-input-lines-edit" id="simwp-input-lines-edit-' . $key . '">
				</td>
				<td>
					<button type="button" class="add simwp-input-lines-button" id="simwp-input-lines-button-' . $key . '"> + </button>
				</td>
				</tr>
			</tbody>
		</table>';
	}
	public function each($idx, $name, $active, $key){
		echo sprintf('<tr>
			<td><input class="hidden" name="%s[]" value="%s" type="text" readonly>
				<label> %s </label>
			</td>
			<td>
				<button type="button" class="delete">x</button>
			</td>
		</tr>', $key, $key, $name);
	}
}
