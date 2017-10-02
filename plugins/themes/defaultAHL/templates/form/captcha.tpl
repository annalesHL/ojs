{**
 * templates/form/captcha.tpl
 *
 * XAVIER
 *
 *}

<div class="captcha">
<label class="captcha">{translate key="common.captchaField"}</label>
{fbvFormSection}
	{fbvElement type="text" label="common.captchaField.operation" id="captcha" name="captcha"}
{/fbvFormSection}
</div>
<script>
$("[name=captcha]").val("{translate key="common.captchaField.result"}");
$("div.captcha").hide();
</script>
