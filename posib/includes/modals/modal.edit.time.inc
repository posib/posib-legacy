<input type="hidden" name="ref" value="<?=$brick->ref ?>" />
<input type="hidden" name="page" value="<?=$brick->page ?>" />

<div class="posib-date-picker">
	<div class="posib-date">
		<input type="hidden" name="datetime" id="posib-datetime" value="<?=$brick->datetime ?>" />
		<table id="posib-calendar">
			<thead>
				<tr>
					<th class="posib-date-month-prev" title="mois précédent">
						<span>&lsaquo;</span>
					</th>
					<th colspan="3" class="posib-date-month-picker">
						Janvier
					</th>
					<th colspan="2" class="posib-date-year-picker">
						2012
					</th>
					<th class="posib-date-month-next" title="mois suivant">
						<span>&rsaquo;</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><abbr title="Lundi">Lun</abbr></th>
					<th><abbr title="Mardi">Mar</abbr></th>
					<th><abbr title="Mercredi">Mer</abbr></th>
					<th><abbr title="Jeudi">Jeu</abbr></th>
					<th><abbr title="Vendredi">Ven</abbr></th>
					<th><abbr title="Samedi">Sam</abbr></th>
					<th><abbr title="Dimanche">Dim</abbr></th>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
				<tr>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="posib-hour posib-field-group">
		<?
			$iHours = date( 'H', $brick->datetime );
			$iMinutes = date( 'i', $brick->datetime );
		?>
		<label for="posib-hours">
			Heure :
		</label>
		<div class="posib-input">
			<select name="hours" id="posib-hours" class="posib-mini">
				<option value="00"<?= $iHours == '00' ? ' selected="selected"' : '' ?>>00</option>
				<option value="01"<?= $iHours == '01' ? ' selected="selected"' : '' ?>>01</option>
				<option value="02"<?= $iHours == '02' ? ' selected="selected"' : '' ?>>02</option>
				<option value="03"<?= $iHours == '03' ? ' selected="selected"' : '' ?>>03</option>
				<option value="04"<?= $iHours == '04' ? ' selected="selected"' : '' ?>>04</option>
				<option value="05"<?= $iHours == '05' ? ' selected="selected"' : '' ?>>05</option>
				<option value="06"<?= $iHours == '06' ? ' selected="selected"' : '' ?>>06</option>
				<option value="07"<?= $iHours == '07' ? ' selected="selected"' : '' ?>>07</option>
				<option value="08"<?= $iHours == '08' ? ' selected="selected"' : '' ?>>08</option>
				<option value="09"<?= $iHours == '09' ? ' selected="selected"' : '' ?>>09</option>
				<option value="10"<?= $iHours == '10' ? ' selected="selected"' : '' ?>>10</option>
				<option value="11"<?= $iHours == '11' ? ' selected="selected"' : '' ?>>11</option>
				<option value="12"<?= $iHours == '12' ? ' selected="selected"' : '' ?>>12</option>
				<option value="13"<?= $iHours == '13' ? ' selected="selected"' : '' ?>>13</option>
				<option value="14"<?= $iHours == '14' ? ' selected="selected"' : '' ?>>14</option>
				<option value="15"<?= $iHours == '15' ? ' selected="selected"' : '' ?>>15</option>
				<option value="16"<?= $iHours == '16' ? ' selected="selected"' : '' ?>>16</option>
				<option value="17"<?= $iHours == '17' ? ' selected="selected"' : '' ?>>17</option>
				<option value="18"<?= $iHours == '18' ? ' selected="selected"' : '' ?>>18</option>
				<option value="19"<?= $iHours == '19' ? ' selected="selected"' : '' ?>>19</option>
				<option value="20"<?= $iHours == '20' ? ' selected="selected"' : '' ?>>20</option>
				<option value="21"<?= $iHours == '21' ? ' selected="selected"' : '' ?>>21</option>
				<option value="22"<?= $iHours == '22' ? ' selected="selected"' : '' ?>>22</option>
				<option value="23"<?= $iHours == '23' ? ' selected="selected"' : '' ?>>23</option>
			</select>
			H
			<select name="minutes" id="posib-minutes" class="posib-mini">
				<option value="00"<?= $iMinutes == '00' ? ' selected="selected"' : '' ?>>00</option>
				<option value="01"<?= $iMinutes == '01' ? ' selected="selected"' : '' ?>>01</option>
				<option value="02"<?= $iMinutes == '02' ? ' selected="selected"' : '' ?>>02</option>
				<option value="03"<?= $iMinutes == '03' ? ' selected="selected"' : '' ?>>03</option>
				<option value="04"<?= $iMinutes == '04' ? ' selected="selected"' : '' ?>>04</option>
				<option value="05"<?= $iMinutes == '05' ? ' selected="selected"' : '' ?>>05</option>
				<option value="06"<?= $iMinutes == '06' ? ' selected="selected"' : '' ?>>06</option>
				<option value="07"<?= $iMinutes == '07' ? ' selected="selected"' : '' ?>>07</option>
				<option value="08"<?= $iMinutes == '08' ? ' selected="selected"' : '' ?>>08</option>
				<option value="09"<?= $iMinutes == '09' ? ' selected="selected"' : '' ?>>09</option>
				<option value="10"<?= $iMinutes == '10' ? ' selected="selected"' : '' ?>>10</option>
				<option value="11"<?= $iMinutes == '11' ? ' selected="selected"' : '' ?>>11</option>
				<option value="12"<?= $iMinutes == '12' ? ' selected="selected"' : '' ?>>12</option>
				<option value="13"<?= $iMinutes == '13' ? ' selected="selected"' : '' ?>>13</option>
				<option value="14"<?= $iMinutes == '14' ? ' selected="selected"' : '' ?>>14</option>
				<option value="15"<?= $iMinutes == '15' ? ' selected="selected"' : '' ?>>15</option>
				<option value="16"<?= $iMinutes == '16' ? ' selected="selected"' : '' ?>>16</option>
				<option value="17"<?= $iMinutes == '17' ? ' selected="selected"' : '' ?>>17</option>
				<option value="18"<?= $iMinutes == '18' ? ' selected="selected"' : '' ?>>18</option>
				<option value="19"<?= $iMinutes == '19' ? ' selected="selected"' : '' ?>>19</option>
				<option value="20"<?= $iMinutes == '20' ? ' selected="selected"' : '' ?>>20</option>
				<option value="21"<?= $iMinutes == '21' ? ' selected="selected"' : '' ?>>21</option>
				<option value="22"<?= $iMinutes == '22' ? ' selected="selected"' : '' ?>>22</option>
				<option value="23"<?= $iMinutes == '23' ? ' selected="selected"' : '' ?>>23</option>
				<option value="24"<?= $iMinutes == '24' ? ' selected="selected"' : '' ?>>24</option>
				<option value="25"<?= $iMinutes == '25' ? ' selected="selected"' : '' ?>>25</option>
				<option value="26"<?= $iMinutes == '26' ? ' selected="selected"' : '' ?>>26</option>
				<option value="27"<?= $iMinutes == '27' ? ' selected="selected"' : '' ?>>27</option>
				<option value="28"<?= $iMinutes == '28' ? ' selected="selected"' : '' ?>>28</option>
				<option value="29"<?= $iMinutes == '29' ? ' selected="selected"' : '' ?>>29</option>
				<option value="30"<?= $iMinutes == '30' ? ' selected="selected"' : '' ?>>30</option>
				<option value="31"<?= $iMinutes == '31' ? ' selected="selected"' : '' ?>>31</option>
				<option value="32"<?= $iMinutes == '32' ? ' selected="selected"' : '' ?>>32</option>
				<option value="33"<?= $iMinutes == '33' ? ' selected="selected"' : '' ?>>33</option>
				<option value="34"<?= $iMinutes == '34' ? ' selected="selected"' : '' ?>>34</option>
				<option value="35"<?= $iMinutes == '35' ? ' selected="selected"' : '' ?>>35</option>
				<option value="36"<?= $iMinutes == '36' ? ' selected="selected"' : '' ?>>36</option>
				<option value="37"<?= $iMinutes == '37' ? ' selected="selected"' : '' ?>>37</option>
				<option value="38"<?= $iMinutes == '38' ? ' selected="selected"' : '' ?>>38</option>
				<option value="39"<?= $iMinutes == '39' ? ' selected="selected"' : '' ?>>39</option>
				<option value="40"<?= $iMinutes == '40' ? ' selected="selected"' : '' ?>>40</option>
				<option value="41"<?= $iMinutes == '41' ? ' selected="selected"' : '' ?>>41</option>
				<option value="42"<?= $iMinutes == '42' ? ' selected="selected"' : '' ?>>42</option>
				<option value="43"<?= $iMinutes == '43' ? ' selected="selected"' : '' ?>>43</option>
				<option value="44"<?= $iMinutes == '44' ? ' selected="selected"' : '' ?>>44</option>
				<option value="45"<?= $iMinutes == '45' ? ' selected="selected"' : '' ?>>45</option>
				<option value="46"<?= $iMinutes == '46' ? ' selected="selected"' : '' ?>>46</option>
				<option value="47"<?= $iMinutes == '47' ? ' selected="selected"' : '' ?>>47</option>
				<option value="48"<?= $iMinutes == '48' ? ' selected="selected"' : '' ?>>48</option>
				<option value="49"<?= $iMinutes == '49' ? ' selected="selected"' : '' ?>>49</option>
				<option value="50"<?= $iMinutes == '50' ? ' selected="selected"' : '' ?>>50</option>
				<option value="51"<?= $iMinutes == '51' ? ' selected="selected"' : '' ?>>51</option>
				<option value="52"<?= $iMinutes == '52' ? ' selected="selected"' : '' ?>>52</option>
				<option value="53"<?= $iMinutes == '53' ? ' selected="selected"' : '' ?>>53</option>
				<option value="54"<?= $iMinutes == '54' ? ' selected="selected"' : '' ?>>54</option>
				<option value="55"<?= $iMinutes == '55' ? ' selected="selected"' : '' ?>>55</option>
				<option value="56"<?= $iMinutes == '56' ? ' selected="selected"' : '' ?>>56</option>
				<option value="57"<?= $iMinutes == '57' ? ' selected="selected"' : '' ?>>57</option>
				<option value="58"<?= $iMinutes == '58' ? ' selected="selected"' : '' ?>>58</option>
				<option value="59"<?= $iMinutes == '59' ? ' selected="selected"' : '' ?>>59</option>
			</select>
		</div>
	</div>
</div>

<div class="posib-field-group">
	<label for="format">
		Format d'affichage :
	</label>
	<div class="posib-input">
		<select name="format" id="format">
			<option value="%d/%m/%Y">13/01/2012</option>
			<option value="%d %B %Y">13 janvier 2012</option>
			<option value="%A %d %B %Y">Vendredi 13 janvier 2012</option>
			<option value="%d/%m/%Y %H:%M">13/01/2012 14:31</option>
			<option value="%d %B %Y, %H:%M">13 janvier 2012, 14h31</option>
			<option value="%A %d %B %Y, %H:%M">Vendredi 13 janvier 2012, 14h31</option>
		</select>
	</div>
</div>
