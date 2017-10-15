{**
 * templates/frontend/pages/partners.tpl
 *
 * SAN	
 *
 * @brief Display AHL partners
 *
 *}
{include file="frontend/components/header.tpl" pageTitleTranslated=$currentJournal->getLocalizedName()}

{* FAQ *}
<div class="page_faq_ahl">

	{call_hook name="Templates::Index::journal"}
  
	<h3>Before the submission</h3>

	<div class="item">
		<div class="question">
		Why should I publish in this journal?
		</div>
		<div class="answer">
		Because!
		</div>
	</div>

	<div class="item">
		<div class="question">
		What are the journal's standards?
		</div>
		<div class="answer">
		The only reasonable answer will be obtained by reading the papers we publish. Be assured that the editorial board applies high international standards in every mathematical domain.
		</div>
	</div>

	<div class="item">
		<div class="question">
		What is the relationship between this journal and Henri Lebesgue?<br>
		Are integrals required for publishing?
		</div>
		<div class="answer">
		No, integrals are not required for publication.<br>
		The name Henri Lebesgue comes from the <a href="https://www.lebesgue.fr">Henri Lebesgue Center</a> that initiated the creation of the journal and continues to actively support it.
		</div>
	</div>

	<div class="item">
		<div class="question">
		I am organizing a conference. Can I publish the proceedings in this journal?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		Are survey papers welcome?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<h3>Submission</h3>

	<div class="item">
		<div class="question">
		Is publication free of charge? Is reading free of charge?
		</div>
		<div class="answer">
		Yes! Once accepted and edited, articles are published online : they are freely accessible
		on the internet website of the journal. The process is free of charge for authors and
		readers.
		</div>
	</div>

	<div class="item">
		<div class="question">
		In which language(s) articles can be submitted?
		</div>
		<div class="answer">
		In all languages we can read.
		</div>
	</div>

	<div class="item">
		<div class="question">
		What are the main steps of the reviewing process?
		</div>
		<div class="answer">
		<b>1.</b> A preliminary short report is asked to a bunch of reviewers.
		   If these reports are mainly negative, the article is rejected rapidly (within at most one month).<br>
		<b>2.</b> Your article is sent to a reviewer for a complete review.<br>
		<b>3.</b> Based on the above report, the editor in charge of your paper together with the editor in chief take the final decision.
		</div>
	</div>

	<div class="item">
		<div class="question">
		When my article is accepted for publication, do I have to send the LaTeX source file?
		Should I use a special LaTeX class?
		</div>
		<div class="answer">
		After acceptation, you have to send your LaTeX source file(s) to the journal.
		We strongly encourage you to use the <a href="">ahlart</a> class but it is not mandatory.
		</div>
	</div>

	<div class="item">
		<div class="question">
		Does the journal accept articles in Numerical Analysis? In applied Statistics?
		</div>
		<div class="answer">
		Yes, this journal is intended to be really generalist. 
		Every topic in pure and applied mathematics is welcome!
		</div>
	</div>

	<div class="item">
		<div class="question">
		Can I attach external files (code, table of values, movies...) to my article?
		</div>
		<div class="answer">
		Yes, it is possible: more files can be attached after your submission is completed by editing the submission.
		</div>
	</div>

	<h3>Publication</h3>

	<div class="item">
		<div class="question">
		Is this journal purely electronic?
		</div>
		<div class="answer">
		Yes.
		</div>
	</div>

	<div class="item">
		<div class="question">
		After acceptation, will I have to transfer the copyright on my article?
		</div>
		<div class="answer">
		No, the authors keep the intellectual property of their inventions.<br>
		The articles are published on a generic <a href="https://creativecommons.org/licenses/by/4.0/">Creative Common BY Licence</a>.
		</div>
	</div>

	<div class="item">
		<div class="question">
		How much time should I wait between acceptance and publication on average?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		Est-ce que les articles seront mis en forme par le journal ?
		</div>
		<div class="answer">
		</div>
	</div>

	<div class="item">
		<div class="question">
		Is this journal referenced in MathScinet and/or ZentralBlatt?
		</div>
		<div class="answer">
		For now, it is not.
		We have required referencement but the process is long. We hope to get a positive answer within at most two years.
		</div>
	</div>

	<div class="item">
		<div class="question">
		Does this journal distribute reprints to authors?
		</div>
		<div class="answer">
		The final version of the article is sent by email to the authors but no paper reprints are issued.
		</div>
	</div>

	<div class="item">
		<div class="question">
		Will I receive an official letter of acceptance/reject?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<h3>Organisation</h3>

	<div class="item">
		<div class="question">
		How was made the editorial board?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		What are the topics represented in the editorial board?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		Why is the editorial board divided in three sections?
		</div>
		<div class="answer">
		The editorial board is organized in three large mathematical areas, namely Algebra and Geometry, Analysis, and Probability and Statistics. This splitting is made to ease the submission process. This should not be understood as a constraint: we warmly welcome articles at the crossroads of distinct areas.
		</div>
	</div>

	<div class="item">
		<div class="question">
		How is this journal funded?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		I want to help this journal. What can I do?
		</div>
		<div class="answer">
		[...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		Can I subscribe to the journal in order to receive automatic notifications after a new article is published?
		</div>
		<div class="answer">
		[...newsletter...]
		</div>
	</div>

	<div class="item">
		<div class="question">
		How long working documents (referee's reports, etc.) are kept?
		</div>
		<div class="answer">
		About one year. After this delay, they are automatically deleted.
		</div>
	</div>

	<h3>Web site</h3>

	<div class="item">
		<div class="question">
		Am I tracked by cookies?
		</div>
		<div class="answer">
		No. We only use one session cookie when you are logged in on the website.<br>
		This session cookie expires in a delay when you close your browser if you do not check the box "Keep me logged in".
		Otherwise it expires in a delay of ... days.
		</div>
	</div>

	<div class="item">
		<div class="question">
		Why is the logo animated on the home page?
		</div>
		<div class="answer">
		Because it is so beautiful.
		</div>
	</div>

	<div class="item">
		<div class="question">
		Why is not the logo animated on all pages?
		</div>
		<div class="answer">
		Because we do not want you to be disturbed when you are submitting your best paper.
		</div>
	</div>
  
</div><!-- .page -->
