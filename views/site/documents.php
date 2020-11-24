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

	<div class="text-center">
		<h4>MEET gyülekezet csatlakozás - presbiteri határozat (MINTA)</h4>
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

