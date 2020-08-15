<?php

$this->title = 'Főoldal';
?>

<div class="hero">
	<div class="heroInner">
		<div id="heroSlider" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<?php for ($i = 0; $i < 8; $i ++) { ?>
					<li data-target="#heroSliderIndicators" data-slide-to="<?=$i;?>" <?php if ($i === 0) { ?>class="active"<?php } ?>></li>
				<?php } ?>
			</ol>
			<div class="carousel-inner">
				<?php for ($i = 0; $i < 8; $i ++) { ?>
					<div class="carousel-item <?php if ($i === 0) { ?>active<?php } ?>">
						<img class="d-block w-100"
							 src="/assets/img/meet_slider_<?=str_pad($i + 1, 2, '0', STR_PAD_LEFT);?>.jpg"
							 alt="MEET" />
					</div>
				<?php } ?>
			</div>
			<a class="carousel-control-prev" href="#heroSlider" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Előző</span>
			</a>
			<a class="carousel-control-next" href="#heroSlider" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Következő</span>
			</a>
		</div>
	</div>
</div>

<div class="teasers">

	<div class="teaser">
		<div class="imgWrapper">
			<img src="/assets/img/meet_nyito_kiskep_01.jpg" alt="Programleírás" />
		</div>
		<h3>Programleírás</h3>
		<p class="teaserText">A Műveld és őrizd! program a teljes egyházat átfogó teremtésvédelmi program, melyet az egyház központilag indít, és
			amelyhez az egyház bármely közössége önkéntesen csatlakozhat. A program önkéntes alapon és elven működik, a közösségek saját
			indíttatásukból jelentkeznek, maguk határozhatják meg céljaikat, szándékaikat, az elköteleződés mértékét, és maguk állíthatják össze a
			közösség teremtésvédelmi programját.</p>
		<a href="/" class="moreLink">Bővebben >></a>
	</div>

	<div class="teaser">
		<div class="imgWrapper">
			<img src="/assets/img/meet_nyito_kiskep_02.jpg" alt="Modulok (Bibliai novények)" />
		</div>
		<h3>Modulok (Bibliai novények)</h3>
		<p class="teaserText">A közösségi szerepvállalás, a teremtést óvó vállalások és elköteleződés mértékétől függően, a közösségek öt különböző
			modulból választhatják részvételüket a programban. A modulokat szimbolizáló bibliai növények mindegyike szép, a maga nemében mindegyik
			teljes, és mindegyik fontos üzenetet hordoz. Neked melyik a kedvenc növényed? Nézd meg bibliai növényeinket.</p>
		<a href="/" class="moreLink">Bővebben >></a>
	</div>

	<div class="teaser">
		<div class="imgWrapper">
			<img src="/assets/img/meet_nyito_kiskep_03.jpg" alt="Jógyakorlatok" />
		</div>
		<h3>Jógyakorlatok</h3>
		<p class="teaserText">A MEET program résztvevő gyülekezetei, iskolái, óvodái, diakóniai intézményei, közösségei és intézményei megosztják
			tapasztalataikat, jógyakorlataikat, illetve a közösségben megvalósuló eseményekről és rendezvényekről hírt adnak. Inspirációra vágysz?
			Te is szerveznél valamilyen teremtésvédelmi programot? Tallózz a résztvevők hírei, programjai, ötletei között.</p>
		<a href="/" class="moreLink">Bővebben >></a>
	</div>

</div>