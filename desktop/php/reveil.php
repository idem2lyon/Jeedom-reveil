<?php
if (!isConnect('admin')) {
throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'reveil');
$eqLogics = eqLogic::byType('reveil');
?>
<div class="row row-overflow">
	<div class="col-lg-2">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-default eqLogicAction" style="width : 50%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
					foreach ($eqLogics as $eqLogic) 
						echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
				?>
			</ul>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend>{{Mes reveils}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 7em;color:#94ca02;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#94ca02"><center>Ajouter</center></span>
			</div>
			<?php
				foreach ($eqLogics as $eqLogic) {
					$opacity = '';
					if ($eqLogic->getIsEnable() != 1) {
						$opacity = '
						-webkit-filter: grayscale(100%);
						-moz-filter: grayscale(100);
						-o-filter: grayscale(100%);
						-ms-filter: grayscale(100%);
						filter: grayscale(100%); opacity: 0.35;';
					}
					echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
					echo "<center>";
					echo '<img src="plugins/reveil/doc/images/reveil_icon.png" height="105" width="95" />';
					echo "</center>";
					echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
					echo '</div>';
				}
			?>
		</div>
	</div>  
	<div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<form class="form-horizontal">
			<fieldset>		
				<legend>
					<i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}  
					<i class="fa fa-cogs eqLogicAction pull-right cursor expertModeVisible" data-action="configure"></i>
					<a class="btn btn-default btn-xs pull-right expertModeVisible eqLogicAction" data-action="copy"><i class="fa fa-copy"></i>{{Dupliquer}}</a>
					<a class="btn btn-success btn-xs eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> Sauvegarder</a>
					<a class="btn btn-danger btn-xs eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> Supprimer</a>
				</legend> 
			</fieldset> 
		</form>	
		<div class="row" style="padding-left:25px;">
			<ul class="nav nav-tabs" id="tab_zones">	
				<li class="active"><a href="#tab_general"><i class="fa fa-cogs"></i> {{Général}}</a></li>
				<li><a href="#tab_condition"><i class="fa fa-pencil"></i> {{Condition exécution}}</a></li>
				<li><a href="#tab_equipement"><i class="fa fa-pencil"></i> {{Equipements}}</a></li>
			</ul>
			<div class="tab-content TabCmdZone">
				<div class="tab-pane active" id="tab_general">
					<form class="form-horizontal">
						<fieldset>
							<div class="form-group ">
								<label class="col-sm-2 control-label">{{Nom du réveil}}
									<sup>
										<i class="fa fa-question-circle tooltips" title="Indiquer le nom de votre réveil" style="font-size : 1em;color:grey;"></i>
									</sup>
								</label>
								<div class="col-sm-5">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du groupe de zones}}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" >{{Objet parent}}
									<sup>
										<i class="fa fa-question-circle tooltips" title="Indiquer l'objet dans lequel le widget de ce réveil apparaîtra sur le dashboard" style="font-size : 1em;color:grey;"></i>
									</sup>
								</label>
								<div class="col-sm-5">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
											foreach (object::all() as $object) 
												echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">
									{{Catégorie}}
									<sup>
										<i class="fa fa-question-circle tooltips" title="Choisissez une catégorie
									Cette information n'est pas obligatoire mais peut être utile pour filtrer les widgets" style="font-size : 1em;color:grey;"></i>
									</sup>
								</label>
								<div class="col-md-8">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
										echo '</label>';
									}
									?>

								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" >
									{{Etat du widget}}
									<sup>
										<i class="fa fa-question-circle tooltips" title="Choisissez les options de visibilité et d'activation
									Si l'équipement n'est pas activé il ne sera pas utilisable dans Jeedom, mais visible sur le dashboard
									Si l'équipement n'est pas visible il sera caché sur le dashboard, mais utilisable dans Jeedom" style="font-size : 1em;color:grey;"></i>
									</sup>
								</label>
								<div class="col-sm-5">
									<label>{{Activer}}</label>
									<input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
									<label>{{Visible}}</label>
									<input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">{{Configurer l'heure du reveil}}
									<sup>
										<i class="fa fa-question-circle tooltips" title="Configurer l'heure du réveil"></i>
									</sup>
								</label>
								<div class=" col-sm-5 input-group">
									<input class="eqLogicAttr form-control input-sm" data-l1key="configuration"  data-l2key="ScheduleCron" placeholder="{{Choisir à l'aide de l'assistant, l'heure de départ de votre réveil}}">
									<span class="input-group-btn">
										<a class="btn btn-success btn-sm ScheduleCron" >
											<i class="fa fa-list-alt"></i>
										</a>
									</span>
								</div>
							</div>
						</fieldset>
					</form>
				</div>				
				<div class="tab-pane" id="tab_condition">
					<form class="form-horizontal">
						<fieldset>
							<legend>{{Les conditions d'exécution :}}
								<sup>
									<i class="fa fa-question-circle tooltips" title="Saisir toutes les conditions d'exécution de la gestion"></i>
								</sup>
								<a class="btn btn-success btn-xs conditionAttr" data-action="add" style="margin-left: 5px;">
									<i class="fa fa-plus-circle"></i>
									{{Ajouter Condition}}
								</a>
							</legend>
							<div class="div_Condition"></div>
						</fieldset>
					</form>
				</div>				
				<div class="tab-pane" id="tab_equipement">
					<form class="form-horizontal">
						<fieldset>
							<legend>{{Liste des equipements à exécuter:}}
								<sup>
									<i class="fa fa-question-circle tooltips" title="Saisir tous les équipements à exécuter"></i>
								</sup>
								<a class="btn btn-success btn-xs ActionAttr" data-action="add" style="margin-left: 5px;">
									<i class="fa fa-plus-circle"></i>
									{{Ajouter un equipement}}
								</a>
							</legend>
							<div class="div_action"></div>
						</fieldset>
					</form>
				</div>		
				</div>
		</div>
		<form class="form-horizontal">
			<fieldset>
				<div class="form-actions">
					<a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
					<a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php include_file('desktop', 'reveil', 'js', 'reveil'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
