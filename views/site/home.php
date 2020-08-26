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
			<a href="/programleiras">
				<img src="/assets/img/meet_nyito_kiskep_01.jpg" alt="Programleírás" />
			</a>
		</div>
		<h3><a href="/programleiras">Programleírás</a></h3>
		<p class="teaserText">A Műveld és őrizd! program a teljes egyházat átfogó teremtésvédelmi program, melyet az egyház központilag indít, és
			amelyhez az egyház bármely közössége önkéntesen csatlakozhat. A program önkéntes alapon és elven működik, a közösségek saját
			indíttatásukból jelentkeznek, maguk határozhatják meg céljaikat, szándékaikat, az elköteleződés mértékét, és maguk állíthatják össze a
			közösség teremtésvédelmi programját.</p>
		<a href="/programleiras" class="moreLink">Bővebben >></a>
	</div>

	<div class="teaser">
		<div class="imgWrapper">
			<a href="/modulok">
				<img src="/assets/img/meet_nyito_kiskep_02.jpg" alt="Modulok (Bibliai novények)" />
			</a>
		</div>
		<h3><a href="/modulok">Modulok (Bibliai novények)</a></h3>
		<p class="teaserText">A közösségi szerepvállalás, a teremtést óvó vállalások és elköteleződés mértékétől függően, a közösségek öt különböző
			modulból választhatják részvételüket a programban. A modulokat szimbolizáló bibliai növények mindegyike szép, a maga nemében mindegyik
			teljes, és mindegyik fontos üzenetet hordoz. Neked melyik a kedvenc növényed? Nézd meg bibliai növényeinket.</p>
		<a href="/modulok" class="moreLink">Bővebben >></a>
	</div>

	<div class="teaser">
		<div class="imgWrapper">
			<a href="/aktivitas">
				<img src="/assets/img/meet_nyito_kiskep_03.jpg" alt="Jógyakorlatok" />
			</a>
		</div>
		<h3><a href="/aktivitas">Jógyakorlatok</a></h3>
		<p class="teaserText">A MEET program résztvevő gyülekezetei, iskolái, óvodái, diakóniai intézményei, közösségei és intézményei megosztják
			tapasztalataikat, jógyakorlataikat, illetve a közösségben megvalósuló eseményekről és rendezvényekről hírt adnak. Inspirációra vágysz?
			Te is szerveznél valamilyen teremtésvédelmi programot? Tallózz a résztvevők hírei, programjai, ötletei között.</p>
		<a href="/aktivitas" class="moreLink">Bővebben >></a>
	</div>

</div>

<a name="resztvevok"></a>
<div class="homeMap">
	<div id="map"></div>
	<div id="mapPopup" class="d-none">
		<span class="name"></span><br />
		<span class="moduleName"></span> | <span class="address"></span><br />
		<span class="fa fa-envelope"></span>&nbsp;<a class="contact" href="javascript:void(0)" data-url="/uzenet?orgId=">Üzenet küldése</a>
	</div>
</div>

<a name="kapcsolat"></a>
<div class="homeContact">

	<div class="row">
		<div class="col-sm-6">
			<h3>Kapcsolat:</h3>
			<div class="contactItem">
				<table>
					<tr>
						<td class="logo">
							<img src="/assets/img/logos/meet_logo.png" alt="MEET" />
						</td>
						<td>
							<h4>Műveld és őrizd! Evangélikus Egyházi Teremtésvédelmi program</h4>
							<ul>
								<li>e-mail: <strong><a href="mailto:meet@lutheran.hu">meet@lutheran.hu</a></strong></li>
								<li>postai cím: <strong>Magyarországi Evangélikus Egyház - Ararát 1085 Budapest, Üllői út 24.</strong></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
			<h3>A program koordinátora:</h3>
			<div class="contactItem">
				<table>
					<tr>
						<td class="logo">
							<img src="/assets/img/logos/ararat_logo.png" alt="Ararát" />
						</td>
						<td>
							<h4>Magyarországi Evangélikus Egyház Ararát Teremtésvédelmi Munkaág</h4>
							<ul>
								<li>honlap: <strong><a href="http://arterm.hu" target="_blank">arterm.hu</a></strong></li>
								<li>facebook: <strong><a href="https://facebook.com/araratteremtesvedelem" target="_blank">@araratteremtesvedelem</a></strong></li>
								<li>e-mail: <strong><a href="mailto:arterm@lutheran.hu">arterm@lutheran.hu</a></strong></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="col-sm-6">
			<h3>Partner oldalak:</h3>
			<div class="contactItem">
				<table>
					<tr>
						<td class="logo">
							<img src="/assets/img/logos/lutheran_logo.png" alt="Evangélikus Egyház" />
						</td>
						<td>
							<h4>Magyarországi Evangélikus Egyház</h4>
							<ul>
								<li>honlap: <strong><a href="https://evangelikus.hu" target="_blank">evangelikus.hu</a></strong></li>
								<li>facebook: <strong><a href="https://facebook.com/evangelikus" target="_blank">@evangelikus</a></strong></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
			<div class="contactItem">
				<table>
					<tr>
						<td class="logo">
							<img src="/assets/img/logos/gyumi_logo.png" alt="GyülMisz" />
						</td>
						<td>
							<h4>Gyülekezeti és Missziói Osztály</h4>
							<ul>
								<li>honlap: <strong><a href="http://gyulmisz.lutheran.hu" target="_blank">gyulmisz.lutheran.hu</a></strong></li>
								<li>facebook: <strong><a href="https://facebook.com/gyulekezetiesmisszioiosztaly" target="_blank">@gyulekezetiesmisszioiosztaly</a></strong></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
			<div class="contactItem">
				<table>
					<tr>
						<td class="logo">
							<img src="/assets/img/logos/ecen_logo.png" alt="ECEN" />
						</td>
						<td>
							<h4>ECEN European Christian Environmental Network</h4>
							<p>(Európai Keresztény Környezetvédelmi Hálózat)</p>
							<ul>
								<li>honlap: <strong><a href="https://ecen.org" target="_blank">ecen.org</a></strong></li>
								<li>facebook: <strong><a href="https://facebook.com/ecen.org">@ecen.org</a></strong></li>
							</ul>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

</div>