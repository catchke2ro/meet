<?php

/**
 * @var Module|null $activeModule
 */

use app\models\Module;

$this->title = 'Dokumentumok';
?>

<div class="stat documents">

	<?=$this->render('/parts/admin-changer', [
		'user' => $user,
	]);?>

	<h1>Dokumentumok</h1>

	<h2 class="text-center">MEET-program bemutató anyagok</h2>
	<p>Bemutató anyagainkat ajánljuk a MEET-program ismertetéséhez például a csatlakozás lehetőségének népszerűsítésére vagy presbiteri ülésekre az előterjesztéshez segédletként.</p>

	<h3>Rövid bemutató</h3>
	<p>Elektronikus, képernyőn lapozható, rövid ismertetője a MEET-programnak: <a href="https://issuu.com/arterm/docs/meet_onepager" target="_blank">https://issuu.com/arterm/docs/meet_onepager</a><br />mobiloptimalizált verzió: <a href="https://issuu.com/arterm/docs/meet_onepager/s/11259325" target="_blank">https://issuu.com/arterm/docs/meet_onepager/s/11259325</a></p>
	<p>Nyomtatóbarát változat (A4-es formátumban, kétoldalas, rövid élen forduló nyomtatásra): <a href="/docs/meet-ONEpager-nyomtatobarat.pdf" target="_blank">PDF letöltése</a><br /><span class="small">Kérünk, hogy mielőtt kinyomtatnád, gondold át, hogy mennyire van szükségetek!</span></p>

	<p></p>

	<h3>Prospektus</h3>

	<p>Elektronikus, képernyőn lapozható, részletes ismertetője a MEET-programnak: <a href="https://issuu.com/arterm/docs/meet_program_e-prospektus" target="_blank">https://issuu.com/arterm/docs/meet_program_e-prospektus</a><br />mobiloptimalizált verzió: <a href="https://issuu.com/arterm/docs/meet_program_e-prospektus/s/11308443" target="_blank">https://issuu.com/arterm/docs/meet_program_e-prospektus/s/11308443</a></p>
	<p>Angol nyelvű változat / English-language presentation: <a href="https://issuu.com/arterm/docs/meet_program_presentation_en" target="_blank">https://issuu.com/arterm/docs/meet_program_presentation_en</a></p>
	<p>Képernyő optimalizált pdf, bemutatásnál kivetítésre alkalmas vagy e-mail mellékletként is küldhető: <a href="/docs/MEET-program_prospektus_szelesvaszon.pdf" target="_blank">PDF letöltése</a></p>

	<h3>Plakát</h3>
	<p>Színes A4-es álló formátumú plakát hirdetőtáblára: <a href="/docs/meet_image_Plakat-A4_press.pdf" target="_blank">PDF letöltése</a></p>

	<p>&nbsp;</p>

	<div class="text-center">
		<h2>MEET gyülekezet csatlakozás - presbiteri határozat (MINTA)</h2>
		<a href="/docs/MEET_gyulekezet_csatlakozas_presbiteri-hatarozat_MINTA.docx" title="DOCX letöltése" class="btn btn-secondary">Minta letöltése</a>
	</div>

	<?php if ($activeModule) { ?>
		<div class="card card-primary mt-5 moduleCI">
			<div class="card-header">
				<h3 class="card-title text-white">Arculati anyagaid - <?=$activeModule->name;?></h3>
			</div>
			<div class="card-body">
				<?php foreach ([
					'meet_fologo_vallalas_%s.pdf' => 'MEET logó és vállalás logó [pdf]',
					'meet_fologo_vallalas_%s_kicsi.png' => 'MEET logó és válallás logó [png]',
					'meet_modul_%s_szines_nagy.png' => 'Vállalás logó, színes háttérrel, nagy [png]',
					'meet_modul_%s_szines_kicsi.png' => 'Vállalás logó, színes háttérrel, kicsi [png]',
					'meet_modul_%s_feher_nagy.png' => 'Vállalás logó, fehér háttérrel, nagy [png]',
					'meet_modul_%s_feher_kicsi.png' => 'Vállalás logó, fehér háttérrel, kicsi [png]',
					'ararat_modul_%s_szines.pdf' => 'Vállalás logó (Ararát), színes háttérrel [pdf]',
					'ararat_modul_%s_szines.ai' => 'Vállalás logó (Ararát), színes háttérrel [ai]',
					'ararat_modul_%s_feher.pdf' => 'Vállalás logó (Ararát), fehér háttérrel [pdf]',
					'ararat_modul_%s_feher.ai' => 'Vállalás logó (Ararát), fehér háttérrel [ai]'
				] as $fileName => $title) { ?>
					<ul>
					<?php if (($link = getLinkForCI($activeModule, $fileName))) { ?>
						<li>
							<a href="<?=$link;?>" target="_blank">
								<?php if (preg_match('/\[(\w+)\]/', $title, $m)) {?>
									<img src="/assets/img/file_types/<?=$m[1];?>.png" alt="<?=$m[1];?>"/>
								<?php } ?>
								<span><?=$title;?></span>
							</a>

						</li>
					<?php } ?>
					</ul>
				<?php } ?>

			</div>
		</div>
	<?php } ?>
</div>

