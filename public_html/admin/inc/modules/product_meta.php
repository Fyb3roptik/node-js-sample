<fieldset>
	<legend>Product Meta Tags</legend>
	<table>
		<tr>
			<th>Keywords</th>
			<td><textarea name="product[meta_keywords]" cols="80" rows="6"><?php echo htmlspecialchars($P->meta_keywords); ?></textarea></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><textarea name="product[meta_description]" cols="80" rows="6"><?php echo htmlspecialchars($P->meta_description); ?></textarea></td>
		</tr>
	</table>
	<input id="save_meta" type="submit" value="Submit" /> or <a href="javascript:void(0);" onclick="change_tab(tabs[0])">Cancel</a>
</fieldset>
