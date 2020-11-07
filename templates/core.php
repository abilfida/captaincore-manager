<!DOCTYPE html>
<html>
<head>
  <title><?php echo ( ! empty( get_option( 'options_business_name' ) ) ? get_option( 'options_business_name' ) . ' - ' : "" ); ?>Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <meta charset="utf-8">
<?php
// Load favicons and wpApiSettings from normal WordPress header
captaincore_header_content_extracted();

// Fetch current user details
$user       = wp_get_current_user();
$role_check = in_array( 'subscriber', $user->roles ) + in_array( 'customer', $user->roles ) + in_array( 'administrator', $user->roles ) + in_array( 'editor', $user->roles );
if ( $role_check ) {
	$belongs_to    = get_user_meta( 'partner', "user_{$user->ID}" );
	$business_name = get_the_title( $belongs_to[0] );
	$business_link = get_metadata( 'partner_link', $belongs_to[0] );
} else {
	$business_name = "";
	$business_link = "";
}
?>
<?php if ( is_plugin_active( 'arve-pro/arve-pro.php' ) ) { ?>
<link rel='stylesheet' id='advanced-responsive-video-embedder-css' href='/wp-content/plugins/advanced-responsive-video-embedder/public/arve.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='arve-pro-css' href='/wp-content/plugins/arve-pro/dist/app.css' type='text/css' media='all' />
<?php } ?>
<link href="<?php echo home_url(); ?>/account/" rel="canonical">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/vuetify@2.3.14/dist/vuetify.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
<link href="/wp-content/plugins/captaincore/public/css/captaincore-public-2020-06-13.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/frappe-charts@1.2.0/dist/frappe-charts.min.css" rel="stylesheet">
</head>
<body>
<div id="app" v-cloak>
	<v-app>
	  <v-app-bar color="primary" dark app style="left:0px">
	 	 <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="d-md-none d-lg-none d-xl-none" v-show="route != 'login' || route != 'connect'"></v-app-bar-nav-icon>
         <v-toolbar-title>
			<v-list flat color="transparent">
		 	<v-list-item href="/account" @click.prevent="goToPath( '/account' )" style="padding:0px;" flat class="not-active">
			 	<v-img :src="configurations.logo" contain :max-width="configurations.logo_width == '' ? 32 : configurations.logo_width" v-if="configurations.logo" class="mr-4"></v-img>
				 {{ configurations.name }}
			</v-list-item>
			</v-list>
			<div class="flex" style="opacity:0;"><textarea id="clipboard" style="height:1px;display:flex;cursor:default"></textarea></div>
		</v-toolbar-title>
		<v-spacer></v-spacer>
      </v-app-bar>
	  <v-navigation-drawer v-model="drawer" app mobile-breakpoint="960" clipped v-if="route != 'login' && route != 'connect'">
      <v-list nav dense>
	  	<v-list-item-group mandatory v-model="selected_nav" color="primary">
        <v-list-item link href="/account/sites" @click.prevent="goToPath( '/account/sites' )">
          <v-list-item-icon>
            <v-icon>mdi-wrench</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Sites</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/account/dns" @click.prevent="goToPath( '/account/dns' )">
          <v-list-item-icon>
            <v-icon>mdi-library-books</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>DNS</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
		<v-list-item link href="/account/health" @click.prevent="goToPath( '/account/health' )">
          <v-list-item-icon>
            <v-icon>mdi-ladybug</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Health</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
		<v-list-item link href="/account/cookbook" @click.prevent="goToPath( '/account/cookbook' )">
        <v-list-item-icon>
            <v-icon>mdi-code-tags</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Cookbook</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/account/handbook" @click.prevent="goToPath( '/account/handbook' )" v-show="role == 'administrator'">
          <v-list-item-icon>
            <v-icon>mdi-map</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Handbook</v-list-item-title>
          </v-list-item-content>
		</v-list-item>
		<v-list-item link href="/account/accounts" @click.prevent="goToPath( '/account/accounts' )">
          <v-list-item-icon>
            <v-icon>mdi-account-card-details</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Accounts</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
		<v-list-item link href="/account/users" @click.prevent="goToPath( '/account/users' )" v-show="role == 'administrator'">
          <v-list-item-icon>
            <v-icon>mdi-account-multiple</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
		</v-list-item-group>
        <v-list-item link :href="billing_link" target="_blank" v-show="billing_link">
          <v-list-item-icon>
            <v-icon>mdi-currency-usd</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Billing  <v-icon small>mdi-open-in-new</v-icon></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
	  <template v-slot:append>
	  <v-menu offset-y top>
      <template v-slot:activator="{ on }">
		<v-list>
		<v-list-item link v-on="on">
			<v-list-item-avatar>
				<v-img :src="gravatar"></v-img>
			</v-list-item-avatar>
			<v-list-item-content>
				<v-list-item-title>{{ current_user_display_name }}</v-list-item-title>
			</v-list-item-content>
			<v-list-item-icon>
				<v-icon>mdi-chevron-up</v-icon>
			</v-list-item-icon>
		</v-list-item>
		</v-list>
      </template>
      <v-list dense>
	  	<v-list-item link href="/account/profile" @click.prevent="goToPath( '/account/profile' )">
          <v-list-item-icon>
            <v-icon>mdi-account-box</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Profile</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
		<v-list-item link href="/account/configurations" @click.prevent="goToPath( '/account/configurations' )" v-show="role == 'administrator'">
          <v-list-item-icon>
            <v-icon>mdi-cogs</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Configurations</v-list-item-title>
          </v-list-item-content>
		</v-list-item>
		<v-list-item link href="/account/defaults" @click.prevent="goToPath( '/account/defaults' )" v-show="role == 'administrator'">
          <v-list-item-icon>
            <v-icon>mdi-application</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Site Defaults</v-list-item-title>
          </v-list-item-content>
		</v-list-item>
		<v-list-item link href="/account/keys" @click.prevent="goToPath( '/account/keys' )"  v-show="role == 'administrator'">
          <v-list-item-icon>
            <v-icon>mdi-key</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>SSH Keys</v-list-item-title>
          </v-list-item-content>
		</v-list-item>
		<v-list-item link v-if="footer.switch_to_link" :href="footer.switch_to_link">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ footer.switch_to_text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
	  	<v-list-item link @click="signOut()">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Log Out</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>
      </template>
	  </v-navigation-drawer>
	  <v-main>
		<v-container fluid style="padding:0px">
		<v-dialog v-model="new_plugin.show" max-width="900px">
		<v-card tile>
		<v-toolbar flat dark color="primary">
			<v-btn icon dark @click.native="new_plugin.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>Add plugin to {{ new_plugin.site_name }}</v-toolbar-title>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-toolbar color="grey lighten-4" dense light flat>
			<v-tabs
				background-color="transparent"
				v-model="new_plugin.tabs"
				mandatory
			>
				<v-tab>From your computer</v-tab>
				<v-tab>From WordPress.org</v-tab>
			</v-tabs>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-tabs-items v-model="new_plugin.tabs">
      <v-tab-item key="0">
		<div class="upload-drag pt-4">
		<div class="upload">
			<div v-if="upload.length" class="mx-3">
				<div v-for="(file, index) in upload" :key="file.id">
					<span>{{ file.name }}</span> -
					<span>{{ file.size | formatSize }}</span> -
					<span v-if="file.error">{{file.error}}</span>
					<span v-else-if="file.success">success</span>
					<span v-else-if="file.active">active
						<v-progress-linear v-model="file.progress"></v-progress-linear>
					</span>
					<span v-else></span>
				</div>
			</div>
			<div v-else>
					<div class="text-center">
						<h4>Drop files anywhere to upload<br/>or</h4>
						<label for="file" class="btn btn-lg btn-primary" style="padding: 0px 8px;">Select Files</label>
					</div>
			</div>
			<div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
				<h3>Drop files to upload</h3>
			</div>
			<div class="upload-drag-btn">
				<file-upload class="btn btn-primary" @input-file="inputFile" post-action="/wp-content/plugins/captaincore/upload.php" :drop="true" v-model="upload" ref="upload"></file-upload>
			</div>
		</div>
		</div>
      </v-tab-item>
			<v-tab-item key="1">
				<v-layout justify-center class="pa-3">
				<v-flex xs12 sm3>
				</v-flex>
				<v-flex xs12 sm6>
					<div class="text-center">
						<v-pagination v-if="new_plugin.api.info && new_plugin.api.info.pages > 1" :length="new_plugin.api.info.pages - 1" v-model="new_plugin.page" :total-visible="7" color="primary" @input="fetchPlugins"></v-pagination>
					</div>
				</v-flex>
				<v-flex xs12 sm3>
					<v-text-field label="Search plugins" light @click:append="new_plugin.search = $event.target.offsetParent.children[0].children[1].value; fetchPlugins()" v-on:keyup.enter="new_plugin.search = $event.target.value; fetchPlugins()" append-icon="search" :loading="new_plugin.loading"></v-text-field>
					<!-- @change.native="new_plugin.search = $event.target.value; fetchPlugins" -->
				</v-flex>
			</v-layout>
			<v-layout row wrap pa-2>
				<v-flex
					v-for="item in new_plugin.api.items"
					:key="item.slug"
					xs4
					pa-2
				>
					<v-card>
					<v-layout style="min-height: 120px;">
					<v-flex xs3 px-2 pt-2>
						<v-img
							:src='item.icons["1x"]'
							contain
						></v-img>
					</v-flex>
					<v-flex xs9 px-2 pt-2>
						<span v-html="item.name"></span>
					</v-flex>
					</v-layout>
						<v-card-actions>
							<v-spacer></v-spacer>
							<div v-if="new_plugin.current_plugins.includes( item.slug )">
							<v-btn small depressed @click="uninstallPlugin( item )">Uninstall</v-btn>
							<v-btn small depressed disabled>Install</v-btn>
							</div>
							<v-btn v-else small depressed @click="installPlugin( item )">Install</v-btn>
						</v-card-actions>
		</v-card>
				</v-flex>
			</v-layout>
      </v-tab-item>
    </v-tabs-items>
		</v-card>
		</v-dialog>
		<v-dialog v-model="new_theme.show" max-width="900px">
		<v-card tile>
		<v-toolbar flat dark color="primary">
			<v-btn icon dark @click.native="new_theme.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>Add theme to {{ new_theme.site_name }}</v-toolbar-title>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-toolbar color="grey lighten-4" dense flat>
			<v-tabs
				background-color="transparent"
				v-model="new_theme.tabs"
				mandatory
			>
				<v-tab>From your computer</v-tab>
				<v-tab>From WordPress.org</v-tab>
			</v-tabs>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-tabs-items v-model="new_theme.tabs">
      <v-tab-item key="0">
		<div class="upload-drag pt-4">
		<div class="upload">
			<div v-if="upload.length" class="mx-3">
				<div v-for="(file, index) in upload" :key="file.id">
					<span>{{ file.name }}</span> -
					<span>{{ file.size | formatSize }}</span> -
					<span v-if="file.error">{{file.error}}</span>
					<span v-else-if="file.success">success</span>
					<span v-else-if="file.active">active
						<v-progress-linear v-model="file.progress"></v-progress-linear>
					</span>
					<span v-else></span>
				</div>
			</div>
			<div v-else>
					<div class="text-center">
						<h4>Drop files anywhere to upload<br/>or</h4>
						<label for="file" class="btn btn-lg btn-primary" style="padding: 0px 8px;">Select Files</label>
					</div>
			</div>
			<div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
				<h3>Drop files to upload</h3>
			</div>
			<div class="upload-drag-btn">
				<file-upload class="btn btn-primary" @input-file="inputFile" post-action="/wp-content/plugins/captaincore/upload.php" :drop="true" v-model="upload" ref="upload"></file-upload>
			</div>
		</div>
		</div>
		</v-tab-item>
			<v-tab-item key="1">
				<v-layout justify-center class="pa-3">
				<v-flex xs12 sm3>
				</v-flex>
				<v-flex xs12 sm6>
					<div class="text-center">
						<v-pagination v-if="new_theme.api.info && new_theme.api.info.pages > 1" :length="new_theme.api.info.pages - 1" v-model="new_theme.page" :total-visible="7" color="primary" @input="fetchThemes"></v-pagination>
					</div>
				</v-flex>
				<v-flex xs12 sm3>
					<v-text-field label="Search themes" light @click:append="new_theme.search = $event.target.offsetParent.children[0].children[1].value; fetchThemes()" v-on:keyup.enter="new_theme.search = $event.target.value; fetchThemes()" append-icon="search" :loading="new_theme.loading"></v-text-field>
				</v-flex>
			</v-layout>
			<v-layout row wrap pa-2>
				<v-flex
					v-if="new_theme.api.items"
					v-for="item in new_theme.api.items"
					:key="item.slug"
					xs4
					pa-2
				>
					<v-card>
					<v-layout style="min-height: 120px;">
					<v-flex xs3 px-2 pt-2>
						<v-img
							:src='item.screenshot_url'
							contain
						></v-img>
					</v-flex>
					<v-flex xs9 px-2 pt-2>
						<span v-html="item.name"></span>
					</v-flex>
					</v-layout>
						<v-card-actions>
							<v-spacer></v-spacer>
							<div v-if="new_theme.current_themes && new_theme.current_themes.includes( item.slug )">
							<v-btn small depressed @click="uninstallTheme( item )">Uninstall</v-btn>
							<v-btn small depressed disabled>Install</v-btn>
							</div>
							<v-btn v-else small depressed @click="installTheme( item )">Install</v-btn>
						</v-card-actions>
					</v-card>
				</v-flex>
			</v-layout>
      </v-tab-item>
    </v-tabs-items>
		</v-card>
		</v-dialog>
		<v-dialog v-model="bulk_edit.show" max-width="600px">
		<v-card tile>
			<v-toolbar flat dark color="primary">
				<v-btn icon dark @click.native="bulk_edit.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Bulk edit on {{ bulk_edit.site_name }}</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
		<v-card-text>
			<h3>Bulk edit {{ bulk_edit.items.length }} {{ bulk_edit.type }}</h3>
			<v-btn v-if="bulk_edit.type == 'plugins'" @click="bulkEditExecute('activate')">Activate</v-btn> <v-btn v-if="bulk_edit.type == 'plugins'" @click="bulkEditExecute('deactivate')">Deactivate</v-btn> <v-btn v-if="bulk_edit.type == 'plugins'" @click="bulkEditExecute('toggle')">Toggle</v-btn> <v-btn @click="bulkEditExecute('delete')">Delete</v-btn>
		</v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_request_site.show" max-width="600px">
			<v-card>
				<v-toolbar flat dense dark color="primary">
					<v-btn icon dark @click.native="dialog_request_site.show = false">
						<v-icon>close</v-icon>
					</v-btn>
					<v-toolbar-title>Create new WordPress site</v-toolbar-title>
					<v-spacer></v-spacer>
				</v-toolbar>
				<v-card-text>
					<v-row>
						<v-col><v-text-field :value="dialog_request_site.request.name" @change.native="dialog_request_site.request.name = $event.target.value" label="Name or Domain" hint="Please enter a name or domain name you wish to use for the new WordPress site." persistent-hint></v-text-field></v-col>
					</v-row>
					<v-row>
						<v-col><v-select v-model="dialog_request_site.request.account_id" label="Account" :items="accounts" item-text="name" item-value="account_id"></v-select></v-col>
					</v-row>
					<v-row>
						<v-col><v-textarea :value="dialog_request_site.request.notes" @change.native="dialog_request_site.request.notes = $event.target.value" label="Notes" hint="Anything else you'd like to mention about this new site? (Optional)" persistent-hint></vtext-area></v-col>
					</v-row>
				</v-card-text>
				<v-card-actions>
					<v-btn color="primary" class="pa-3" @click="requestSite()">Request New Site</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_mailgun_config.show" max-width="500px">
		<v-card tile>
			<v-toolbar flat dark color="primary">
				<v-btn icon dark @click.native="dialog_mailgun_config.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Configure Mailgun for {{ dialog_site.site.name }}</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text class="mt-4">
				<v-text-field label="Mailgun Subdomain" :value="dialog_site.site.mailgun" @change.native="dialog_site.site.mailgun = $event.target.value"></v-text-field>
				<v-flex xs12>
					<v-btn  color="primary" dark @click="saveMailgun()">Save</v-btn>
				</v-flex>
			</v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_fathom.show" max-width="500px">
		<v-card tile>
			<v-toolbar flat dark color="primary">
				<v-btn icon dark @click.native="dialog_fathom.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Configure Fathom for {{ dialog_fathom.site.name }}</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text>
				<v-progress-linear :indeterminate="true" v-if="dialog_fathom.loading"></v-progress-linear>
				<table>
				<tr v-for="tracker in dialog_fathom.environment.fathom">
					<td class="pa-1"><v-text-field v-model="tracker.domain" label="Domain"></v-text-field></td>
					<td class="pa-1"><v-text-field v-model="tracker.code" label="Code"></v-text-field></td>
					<td>
						<v-icon small @click="deleteFathomItem(tracker)">delete</v-icon>
					</td>
				</tr>
				</table>
				<v-flex xs12 class="text-right">
				<v-btn fab small @click="newFathomItem">
					<v-icon dark>add</v-icon>
				</v-btn>
				</v-flex>
				<v-flex xs12>
					<v-btn  color="primary" dark @click="saveFathomConfigurations()">Save Fathom configurations</v-btn>
				</v-flex>
		</v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_fathom.editItem" max-width="500px">
        <v-card>
          <v-card-title>
            <span class="headline">Edit Item</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="dialog_fathom.editedItem.domain" label="Domain"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="dialog_fathom.editedItem.code" label="Code"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="configureFathomClose">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="configureFathomSave">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
	  <v-dialog v-model="new_recipe.show" max-width="800px">
	  	<v-card tile style="margin:auto;max-width:800px">
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="new_recipe.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>New Recipe</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="new_recipe.title" @change.native="new_recipe.title = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-textarea label="Content" persistent-hint hint="Bash script and WP-CLI commands welcomed." auto-grow :value="new_recipe.content" @change.native="new_recipe.content = $event.target.value" spellcheck="false"></v-textarea>
				</v-flex>
				<v-flex xs12 pa-2 v-if="role == 'administrator'">
					<v-switch label="Public" v-model="new_recipe.public" persistent-hint hint="Public by default. Turning off will make the recipe only viewable and useable by you." :false-value="0" :true-value="1"></v-switch>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="addRecipe()">
						Add New Recipe
					</v-btn>
				</v-flex>
			</v-layout>
			</v-container>
			</v-card-text>
		</v-card>
	  </v-dialog>
	  <v-dialog v-model="dialog_new_account.show" max-width="800px" persistent scrollable v-if="role == 'administrator'">
		<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_new_account.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>New Account</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_new_account.name" @change.native="dialog_new_account.name = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="createSiteAccount()">
						Create Account
					</v-btn>
				</v-flex>
			</v-layout>
			</v-container>
			</v-card-text>
		</v-card>
	  </v-dialog>
	  <v-dialog v-model="dialog_edit_account.show" max-width="800px" persistent scrollable>
		<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_edit_account.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Edit Account</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_edit_account.account.name" @change.native="dialog_edit_account.account.name = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-select v-if="typeof dialog_account.records.users == 'object'" label="Billing User" :items="dialog_account.records.users" :item-text="item => `${item.name} - ${item.email}`" item-value="user_id" v-model="dialog_edit_account.account.billing_user_id"></v-select>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="updateSiteAccount()">
						Save Account
					</v-btn>
				</v-flex>
			</v-layout>
			</v-container>
			</v-card-text>
		</v-card>
	  </v-dialog>
	  <v-dialog v-model="dialog_cookbook.show" max-width="800px" persistent scrollable>
		<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_cookbook.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Edit Recipe</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_cookbook.recipe.title" @change.native="dialog_cookbook.recipe.title = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-textarea label="Content" persistent-hint hint="Bash script and WP-CLI commands welcomed." auto-grow :value="dialog_cookbook.recipe.content" @change.native="dialog_cookbook.recipe.content = $event.target.value" spellcheck="false"></v-textarea>
				</v-flex>
				<v-flex xs12 pa-2 v-if="role == 'administrator'">
					<v-switch label="Public" v-model="dialog_cookbook.recipe.public" persistent-hint hint="Public by default. Turning off will make the recipe only viewable and useable by you." false-value="0" true-value="1"></v-switch>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="updateRecipe()">
						Save Recipe
					</v-btn>
				</v-flex>
			</v-layout>
			</v-container>
			</v-card-text>
		</v-card>
	  </v-dialog>
	  <v-dialog v-model="dialog_user.show" max-width="800px" persistent scrollable>
		<v-card tile v-if="typeof dialog_user.user == 'object'">
			<v-toolbar dense flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_user.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Edit user {{ dialog_user.user.name }}</v-toolbar-title>
				<div class="flex-grow-1"></div>
			</v-toolbar>
			<v-card-text>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_user.user.name" @change.native="dialog_user.user.name = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-text-field label="Email" :value="dialog_user.user.email" @change.native="dialog_user.user.email = $event.target.value"></v-text-field>
				</v-flex>
				<v-autocomplete :items="accounts" item-text="name" item-value="account_id" v-model="dialog_user.user.account_ids" label="Accounts" chips multiple deletable-chips></v-autocomplete>
				<v-alert :value="true" type="error" v-for="error in dialog_user.errors" class="mt-5">{{ error }}</v-alert>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="saveUser()">
						Save User
					</v-btn>
				</v-flex>
			</v-card-text>
		</v-card>
	  </v-dialog>
	  <v-dialog v-model="new_key.show" max-width="800px" v-if="role == 'administrator'">
		<v-card tile style="margin:auto;max-width:800px">
		<v-toolbar flat color="grey lighten-4">
			<v-btn icon @click.native="new_key.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>New SSH Key</v-toolbar-title>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-card-text style="max-height: 100%;">
		<v-container>
		<v-layout row wrap>
			<v-flex xs12 pa-2>
				<v-text-field label="Name" :value="new_key.title" @change.native="new_key.title = $event.target.value"></v-text-field>
			</v-flex>
			<v-flex xs12 pa-2>
				<v-textarea label="Private Key" persistent-hint hint="Contents of your private key file. Typically named something like 'id_rsa'. The corresponding public key will need to added to your host provider." auto-grow :value="new_key.key" @change.native="new_key.key = $event.target.value" spellcheck="false"></v-textarea>
			</v-flex>

			<v-flex xs12 text-right pa-0 ma-0>
				<v-btn color="primary" dark @click="addNewKey()">
					Add New SSH Key
				</v-btn>
			</v-flex>
			</v-flex>
			</v-layout>
		</v-container>
		</v-card-text>
		</v-card>
	</v-dialog>
	<v-dialog v-model="dialog_key.show" v-if="role == 'administrator'" max-width="800px" v-if="role == 'administrator'" persistent scrollable>
		<v-card tile>
		<v-toolbar flat color="grey lighten-4">
			<v-btn icon @click.native="dialog_key.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>Edit SSH Key</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-chip color="primary" text-color="white" text>{{ dialog_key.key.fingerprint }}</v-chip>
		</v-toolbar>
		<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_key.key.title" @change.native="dialog_key.key.title = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-textarea label="Private Key" persistent-hint hint="Enter new private key to override existing key. The current key is not viewable." auto-grow :value="dialog_key.key.key" @change.native="dialog_key.key.key = $event.target.value" spellcheck="false"></v-textarea>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn @click="deleteKey()" class="mr-2">
						Delete SSH Key
					</v-btn>
					<v-btn color="primary" dark @click="updateKey()">
						Save SSH Key
					</v-btn>
				</v-flex>
			</v-layout>
			</v-container>
			</v-card-text>
		</v-card>
	</v-dialog>
	  <v-dialog v-model="new_process.show" max-width="800px" v-if="role == 'administrator'" persistent scrollable>
		<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="new_process.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>New Process</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="new_process.name" @change.native="new_process.name = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-text-field label="Time Estimate" hint="Example: 15 minutes" persistent-hint :value="new_process.time_estimate" @change.native="new_process.time_estimate = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-select :items='[{"text":"As needed","value":"as-needed"},{"text":"Daily","value":"1-daily"},{"text":"Weekly","value":"2-weekly"},{"text":"Monthly","value":"3-monthly"},{"text":"Yearly","value":"4-yearly"}]' label="Repeat" v-model="new_process.repeat_interval"></v-select>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-text-field label="Repeat Quantity"  hint="Example: 2 or 3 times" persistent-hint :value="new_process.repeat_quantity" @change.native="new_process.repeat_quantity = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-autocomplete :items="process_roles" item-text="name" item-value="role_id" label="Role" hide-details v-model="new_process.roles"></v-autocomplete>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-textarea label="Description" persistent-hint hint="Steps to accomplish this process. Markdown enabled." auto-grow :value="new_process.description" @change.native="new_process.description = $event.target.value"></v-textarea>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="addNewProcess()">
						Add New Process
					</v-btn>
				</v-flex>
				</v-flex>
				</v-layout>
			</v-container>
			</v-card-text>
			</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_edit_process.show" persistent max-width="800px" v-if="role == 'administrator'" persistent scrollable>
		<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_edit_process.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Edit Process</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
			<v-container>
			<v-layout row wrap>
				<v-flex xs12 pa-2>
					<v-text-field label="Name" :value="dialog_edit_process.process.name" @change.native="dialog_edit_process.process.name = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-text-field label="Time Estimate" hint="Example: 15 minutes" persistent-hint :value="dialog_edit_process.process.time_estimate" @change.native="dialog_edit_process.process.time_estimate = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-select :items='[{"text":"As needed","value":"as-needed"},{"text":"Daily","value":"1-daily"},{"text":"Weekly","value":"2-weekly"},{"text":"Monthly","value":"3-monthly"},{"text":"Yearly","value":"4-yearly"}]' label="Repeat" v-model="dialog_edit_process.process.repeat_interval"></v-select>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-text-field label="Repeat Quantity" hint="Example: 2 or 3 times" persistent-hint :value="dialog_edit_process.process.repeat_quantity" @change.native="dialog_edit_process.process.repeat_quantity = $event.target.value"></v-text-field>
				</v-flex>
				<v-flex xs12 sm3 pa-2>
					<v-autocomplete :items="process_roles" item-text="name" item-value="role_id" label="Role" hide-details v-model="dialog_edit_process.process.roles"></v-autocomplete>
				</v-flex>
				<v-flex xs12 pa-2>
					<v-textarea label="Description" persistent-hint hint="Steps to accomplish this process. Markdown enabled." auto-grow :value="dialog_edit_process.process.description" @change.native="dialog_edit_process.process.description = $event.target.value"></v-textarea>
				</v-flex>
				<v-flex xs12 text-right pa-0 ma-0>
					<v-btn color="primary" dark @click="saveProcess()">
						Save Process
					</v-btn>
				</v-flex>
				</v-flex>
				</v-layout>
			</v-container>
			</v-card-text>
			</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_handbook.show" v-if="role == 'administrator'" scrollable persistent>
			<v-card tile>
			<v-toolbar flat color="grey lighten-4">
				<v-btn icon @click.native="dialog_handbook.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>{{ dialog_handbook.process.name }} <v-chip color="primary" text-color="white" text v-show="dialog_handbook.process.roles != ''">{{ dialog_handbook.process.roles }}</v-chip></v-toolbar-title>
				<v-spacer></v-spacer>
				<v-toolbar-items>
					<v-btn text @click="editProcess()">Edit</v-btn>
				</v-toolbar-items>
			</v-toolbar>
			<v-card-text style="max-height: 100%;">
				<div class="caption my-3">
					<v-icon small v-show="dialog_handbook.process.time_estimate != ''" style="padding:0px 5px">mdi-clock-outline</v-icon>{{ dialog_handbook.process.time_estimate }} 
					<v-icon small v-show="dialog_handbook.process.repeat != '' && dialog_handbook.process.repeat != null" style="padding:0px 5px">mdi-calendar-repeat</v-icon>{{ dialog_handbook.process.repeat }} 
					<v-icon small v-show="dialog_handbook.process.repeat_quantity != '' && dialog_handbook.process.repeat_quantity != null" style="padding:0px 5px">mdi-repeat</v-icon>{{ dialog_handbook.process.repeat_quantity }}
				</div>
				<span v-html="dialog_handbook.process.description"></span>
			</v-card-text>
			</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_update_settings.show" max-width="500px">
		<v-card tile>
			<v-toolbar flat dark color="primary">
				<v-btn icon dark @click.native="dialog_update_settings.show = false">
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title>Save settings for {{ dialog_site.site.name }}</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text>
				<v-switch label="Automatic Updates" v-model="dialog_update_settings.environment.updates_enabled" :false-value="0" :true-value="1" class="mt-7"></v-switch>
				<v-select
					:items="dialog_update_settings.plugins"
					item-text="title"
					item-value="name"
					v-model="dialog_update_settings.environment.updates_exclude_plugins"
					label="Excluded Plugins"
					multiple
					chips
					persistent-hint
				></v-select>
				<v-select
					:items="dialog_update_settings.themes"
					item-text="title"
					item-value="name"
					v-model="dialog_update_settings.environment.updates_exclude_themes"
					label="Excluded Themes"
					multiple
					chips
					persistent-hint
				></v-select>
				<v-progress-linear :indeterminate="true" v-if="dialog_update_settings.loading"></v-progress-linear>
				<v-btn @click="saveUpdateSettings()">Save Update Settings</v-btn>
			</v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_theme_and_plugin_checks.show" width="500">
        <v-card tile>
          <v-toolbar flat dark color="primary">
				<v-btn icon dark @click.native="dialog_theme_and_plugin_checks.show = false">
              <v-icon>close</v-icon>
            </v-btn>
				<v-toolbar-title>Theme & plugin checks for {{ dialog_theme_and_plugin_checks.site.name }}</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>

				<p>Enables daily checks to verify a theme/plugin is a certain status (activate/inactive). Will email notify if a check fails.</p>

				<v-switch label="Theme & Plugin Checks" v-model="dialog_theme_and_plugin_checks.theme_and_plugin_checks" false-value="0" true-value="1"></v-switch>
				<v-data-table
					:items='[{ slug: "wordpress-seo", status: "active" },{ slug: "enhanced-e-commerce-for-woocommerce-store", status: "active"}]'
					hide-default-footer
					hide-default-header
					class="elevation-1"
					v-show="dialog_theme_and_plugin_checks.theme_and_plugin_checks == 1"
				>
				<template v-slot:body="{ items }">
				<tbody>
					<tr v-for="item in items">
						<td>
							<v-text-field v-model="item.slug" label="Slug" required></v-text-field>
						</td>
						<td class="text-right">
							<v-select
								:items='["active","inactive","active-network"]'
								box
								label="Status"
								:value="item.status"
							>
							</v-select>
						</td>
						<td class="justify-center layout px-0">
							<v-icon small @click="deleteItem(item)">delete</v-icon>
						</td>
					</tr>
					<tr>
						<td colspan="100%" class="text-right">
							<v-btn @click="deleteItem(props.item)">
								Add new check
							</v-btn>
						</td>
					</tr>
				</tbody>
				</template>
			  </v-data-table>
				<v-progress-linear :indeterminate="true" v-if="dialog_theme_and_plugin_checks.loading"></v-progress-linear>
				<v-btn @click="savethemeAndPluginChecks()">Save Checks</v-btn>
          </v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_new_domain.show" scrollable width="500">
		<v-card>
			<v-toolbar flat dark color="primary">
			<v-btn icon dark @click.native="dialog_new_domain.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>Add Domain</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<v-card-text>
				<v-text-field :value="dialog_new_domain.domain.name" @change.native="dialog_new_domain.domain.name = $event.target.value" label="Domain Name" required class="mt-3"></v-text-field>
				<v-autocomplete :items="accounts" item-text="name" item-value="account_id" v-model="dialog_new_domain.domain.account_id" label="Account" required></v-autocomplete>
				<v-alert
					:value="true"
					type="error"
					v-for="error in dialog_new_domain.errors"
					>
					{{ error }}
				</v-alert>
				<v-progress-linear indeterminate rounded height="6" class="mb-3" v-show="dialog_new_domain.loading"></v-progress-linear>
				<v-flex xs12 text-right>
					<v-btn color="primary" dark @click="addDomain()">
						Add domain
					</v-btn>
				</v-flex>
			</v-card-text>
		</v-card>
		</v-dialog>
		<v-dialog v-model="dialog_configure_defaults.show" scrollable width="980">
		<v-card>
			<v-toolbar flat dark color="primary">
			<v-btn icon dark @click.native="dialog_configure_defaults.show = false">
				<v-icon>close</v-icon>
			</v-btn>
			<v-toolbar-title>Configure Defaults</v-toolbar-title>
				<v-spacer></v-spacer>
			</v-toolbar>
			<template v-if="dialog_configure_defaults.loading">
				<v-progress-linear :indeterminate="true"></v-progress-linear>
			</template>
			<v-card-text>
				<template v-if="dialog_account.show">
				<v-alert :value="true" type="info" class="mb-4 mt-4">
					When new sites are added to the account <strong>{{ dialog_account.records.account.name }}</strong> then the following default settings will be applied.  
				</v-alert>
				<v-layout wrap>
					<v-flex xs6 pr-2><v-text-field :value="dialog_account.records.account.defaults.email" @change.native="dialog_account.records.account.defaults.email = $event.target.value" label="Default Email" required></v-text-field></v-flex>
					<v-flex xs6 pl-2><v-autocomplete :items="timezones" label="Default Timezone" v-model="dialog_account.records.account.defaults.timezone"></v-autocomplete></v-flex>
				</v-layout>
				<v-layout wrap>
					<v-flex><v-autocomplete label="Default Recipes" v-model="dialog_account.records.account.defaults.recipes" ref="default_recipes" :items="recipes" item-text="title" item-value="recipe_id" multiple chips deletable-chips :menu-props="{ closeOnContentClick:true, openOnClick: false }"></v-autocomplete></v-flex>
				</v-layout>
				<span class="body-2">Default Users</span>
				<v-data-table
					:items="dialog_account.records.account.defaults.users"
					hide-default-header
					hide-default-footer
					v-if="typeof dialog_account.records.account.defaults.users == 'object'"
				>
				<template v-slot:body="{ items }">
				<tbody>
					<tr v-for="(item, index) in items" style="border-bottom: 0px;">
						<td class="pa-1"><v-text-field :value="item.username" @change.native="item.username = $event.target.value" label="Username"></v-text-field></td>
						<td class="pa-1"><v-text-field :value="item.email" @change.native="item.email = $event.target.value" label="Email"></v-text-field></td>
						<td class="pa-1"><v-text-field :value="item.first_name" @change.native="item.first_name = $event.target.value" label="First Name"></v-text-field></td>
						<td class="pa-1"><v-text-field :value="item.last_name" @change.native="item.last_name = $event.target.value" label="Last Name"></v-text-field></td>
						<td class="pa-1" style="width:145px;"><v-select :value="item.role" v-model="item.role" :items="roles" label="Role" item-text="name"></v-select></td>
						<td class="pa-1"><v-btn text small icon color="primary" @click="deleteUserValue( index )"><v-icon small>mdi-delete</v-icon></v-btn></td>
					</tr>
				</tbody>
				</template>
					<template v-slot:footer>
					<tr style="border-top: 0px;">
						<td colspan="5" style="padding:0px;">
							<v-btn depressed small class="ma-0 mb-3" @click="addDefaultsUser()">Add Additional User</v-btn>
						</td>
					</tr>
					</template>
				</v-data-table>

				<v-flex xs12 text-right>
					<v-btn color="primary" dark @click="saveDefaults()">
						Save Changes
					</v-btn>
				</v-flex>
				</template>
			</v-card-text>
		</v-card>
		</v-dialog>
				<v-dialog
					v-model="dialog_modify_plan.show"
					transition="dialog-bottom-transition"
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_modify_plan.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Edit plan for {{ dialog_account.records.account.name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text class="mt-4">
						<v-layout row wrap>
						<v-flex xs12>
						<v-select
							@change="loadHostingPlan()"
							v-model="dialog_modify_plan.selected_plan"
							label="Plan Name"
							:items="dialog_modify_plan.hosting_plans.map( plan => plan.name )"
							:value="dialog_modify_plan.plan.name"
						></v-select>
						</v-flex>
						<v-flex xs12>
						<v-select
							@change="calculateHostingPlan()"
							v-model="dialog_modify_plan.plan.interval"
							label="Plan Interval"
							:items="[{ text: 'Yearly', value: '12' },{ text: 'Monthly', value: '1' },{ text: 'Quarterly', value: '4' },{ text: 'Biannual', value: '6' }]"
							:value="dialog_modify_plan.plan.interval"
						></v-select>
						</v-flex>
						</v-layout>
						<v-layout v-if="typeof dialog_modify_plan.plan.name == 'string' && dialog_modify_plan.plan.name == 'Custom'" row wrap>
							<v-flex xs3 pa-1><v-text-field label="Storage (GBs)" :value="dialog_modify_plan.plan.limits.storage" @change.native="dialog_modify_plan.plan.limits.storage = $event.target.value"></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Visits" :value="dialog_modify_plan.plan.limits.visits" @change.native="dialog_modify_plan.plan.limits.visits = $event.target.value"></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Sites" :value="dialog_modify_plan.plan.limits.sites" @change.native="dialog_modify_plan.plan.limits.sites = $event.target.value"></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Price" :value="dialog_modify_plan.plan.price" @change.native="dialog_modify_plan.plan.price = $event.target.value"></v-text-field></v-flex>
						</v-layout>
						<v-layout v-else row wrap>
							<v-flex xs3 pa-1><v-text-field label="Storage (GBs)" :value="dialog_modify_plan.plan.limits.storage" disabled></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Visits" :value="dialog_modify_plan.plan.limits.visits" disabled></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Sites" :value="dialog_modify_plan.plan.limits.sites" disabled ></v-text-field></v-flex>
							<v-flex xs3 pa-1><v-text-field label="Price" :value="dialog_modify_plan.plan.price" disabled ></v-text-field></v-flex>
						</v-layout>
						<h3 class="title" v-show="typeof dialog_modify_plan.plan.addons == 'object' && dialog_modify_plan.plan.addons" style="margin-top: 1em;">Addons</h3>
						<v-layout row wrap v-for="(addon, index) in dialog_modify_plan.plan.addons">
						<v-flex xs7 pa-1>
							<v-textarea auto-grow rows="1" label="Name" :value="addon.name" @change.native="addon.name = $event.target.value"></v-textarea>
						</v-flex>
						<v-flex xs2 pa-1>
							<v-text-field label="Quantity" :value="addon.quantity" @change.native="addon.quantity = $event.target.value">
						</v-flex>
						<v-flex xs2 pa-1>
							<v-text-field label="Price" :value="addon.price" @change.native="addon.price = $event.target.value">
						</v-flex>
						<v-flex xs1>
							<v-btn small text icon @click="removeAddon(index)"><v-icon>delete</v-icon></v-btn>
						</v-flex>
						</v-layout>
						<v-btn small style="margin:0px;" @click="addAddon()">
							Add Addon
						</v-btn>
						<v-layout>
						<v-flex xs12 text-right>
							<v-btn color="primary" dark style="margin:0px;" @click="updatePlan()">
								Save Changes
							</v-btn>
						</v-flex>
						</v-layout>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-if="role == 'administrator'"
					v-model="dialog_log_history.show"
					scrollable
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_log_history.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Log History</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-data-table
						:headers="header_timeline"
						:items="dialog_log_history.logs"
						:footer-props="{ itemsPerPageOptions: [50,100,250,{'text':'All','value':-1}] }"
						class="timeline"
					>
				<template v-slot:body="{ items }">
				<tbody>
				<tr v-for="item in items">
					<td class="justify-center">{{ item.created_at | pretty_timestamp_epoch }}</td>
					<td class="justify-center">{{ item.author }}</td>
					<td class="justify-center">{{ item.name }}</td>
					<td class="justify-center" v-html="item.description"></td>
					<td>
						<v-btn text icon @click="dialog_log_history.show = false; editLogEntry(item.websites, item.process_log_id)" v-if="role == 'administrator'">
							<v-icon small>edit</v-icon>
						</v-btn>
						{{ item.websites.map( site => site.name ).join(" ") }}
					</td>
				</tr>
				</tbody>
				</template>
			</v-data-table>
					</v-card-text>
				</v-dialog>
				<v-dialog
					v-if="role == 'administrator'"
					v-model="dialog_new_log_entry.show"
					transition="dialog-bottom-transition"
					scrollable
					persistent
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_new_log_entry.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Add a new log entry <span v-if="dialog_new_log_entry.site_name">for {{ dialog_new_log_entry.site_name }}</span></v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-autocomplete
							v-model="dialog_new_log_entry.process"
							:items="processes"
							item-text="name"
							item-value="process_id"
						>
						<template v-slot:item="data">
							<template v-if="typeof data.item !== 'object'">
								<div v-text="data.item"></div>
							</template>
							<template v-else>
								<div>
									<v-list-item-title v-html="data.item.name"></v-list-item-title>
									<v-list-item-subtitle v-html="data.item.repeat_interval + ' - ' + data.item.roles"></v-list-item-subtitle>
								</div>
							</template>
						</template>
						</v-autocomplete>
						<v-autocomplete
							v-model="dialog_new_log_entry.sites"
							:items="sites"
							item-text="name"
							return-object
							chips
							deletable-chips 
							multiple
						>
						</v-autocomplete>
						<v-textarea label="Description" auto-grow :value="dialog_new_log_entry.description" @change.native="dialog_new_log_entry.description = $event.target.value"></v-textarea>
						<v-flex xs12 text-right>
							<v-btn color="primary" dark style="margin:0px;" @click="newLogEntry()">
								Add Log Entry
							</v-btn>
						</v-flex>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-if="role == 'administrator'"
					v-model="dialog_edit_log_entry.show"
					transition="dialog-bottom-transition"
					scrollable
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_edit_log_entry.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Edit log entry <span v-if="dialog_edit_log_entry.site_name">for {{ dialog_edit_log_entry.site_name }}</span></v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-text-field
							v-model="dialog_edit_log_entry.log.created_at_raw"
							label="Date"
						></v-text-field>
						<v-autocomplete
							v-model="dialog_edit_log_entry.log.process_id"
							:items="processes"
							item-text="name"
							item-value="process_id"
						>
						<template v-slot:item="data">
							<template v-if="typeof data.item !== 'object'">
								<div v-text="data.item"></div>
							</template>
							<template v-else>
								<div>
									<v-list-item-title v-html="data.item.name"></v-list-item-title>
									<v-list-item-subtitle v-html="data.item.repeat_interval + ' - ' + data.item.roles"></v-list-item-subtitle>
								</div>
							</template>
						</template>
						</v-autocomplete>
						<v-autocomplete
							v-model="dialog_edit_log_entry.log.websites"
							:items="sites"
							item-text="name"
							return-object
							chips
							deletable-chips 
							multiple
						>
						</v-autocomplete>
						<v-textarea label="Description" auto-grow :value="dialog_edit_log_entry.log.description_raw" @change.native="dialog_edit_log_entry.log.description_raw = $event.target.value"></v-textarea>
						<v-flex xs12 text-right>
							<v-btn color="primary" dark style="margin:0px;" @click="updateLogEntry()">
								Save Log Entry
							</v-btn>
						</v-flex>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_mailgun.show"
					transition="dialog-bottom-transition"
					scrollable
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_mailgun.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Mailgun Logs for {{ dialog_mailgun.site.name }} (Last 30 days)</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-data-table
							:options.sync="dialog_mailgun.pagination"
							:headers='[{"text":"Timestamp","value":"timestamp"},{"text":"Description","value":"description"},{"text":"Event","value":"event"}]'
							:items="dialog_mailgun.response.items"
							:items-per-page="50"
							:footer-props="{ itemsPerPageOptions: [100] }"
							@update:page="fetchMailgunPage"
						>
						<template v-slot:body="{ items }">
						<tbody>
						<tr v-for="item in items" :key="item.event.id">
							<td class="justify-center">{{ item.timestamp | pretty_timestamp_epoch }}</td>
							<td class="justify-center">{{ item.description }}</td>
							<td class="justify-center">{{ item.event }}</td>
						</tr>
						</tbody>
						</template>
						</v-data-table>
						<v-progress-circular indeterminate color="primary" class="ma-2" size="24" v-show="dialog_mailgun.loading"></v-progress-circular>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_backup_snapshot.show"
					width="500"
					transition="dialog-bottom-transition"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_backup_snapshot.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Download Snapshot {{ dialog_backup_snapshot.site.name }} </v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-text-field name="Email" v-model="dialog_backup_snapshot.email"></v-text-field>
					
							<v-switch v-model="dialog_backup_snapshot.filter_toggle" label="Everything"></v-switch>
							<div v-show="dialog_backup_snapshot.filter_toggle === false">
								<v-checkbox small hide-details v-model="dialog_backup_snapshot.filter_options" label="Database" value="database"></v-checkbox>
 								<v-checkbox small hide-details v-model="dialog_backup_snapshot.filter_options" label="Themes" value="themes"></v-checkbox>
								<v-checkbox small hide-details v-model="dialog_backup_snapshot.filter_options" label="Plugins" value="plugins"></v-checkbox>
								<v-checkbox small hide-details v-model="dialog_backup_snapshot.filter_options" label="Uploads" value="uploads"></v-checkbox>
								<v-checkbox small hide-details v-model="dialog_backup_snapshot.filter_options" label="Everything Else" value="everything-else"></v-checkbox>
								<v-spacer><br /></v-spacer>
							</div>
						<v-btn @click="downloadBackupSnapshot()">
							Download Snapshot
						</v-btn>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_delete_user.show"
					scrollable
					width="500px"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_delete_user.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Delete user</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-layout row wrap>
						 <v-flex xs12 pa-2>
								<span>To delete <strong>{{ dialog_delete_user.username }}</strong> from <strong>{{ dialog_delete_user.site.name }}</strong> ({{ dialog_delete_user.site.environment_selected }}), please reassign posts to another user.</span>
								<v-autocomplete
									:items="dialog_delete_user.users"
									return-object
									v-model="dialog_delete_user.reassign"
									item-text="user_login"
									label="Reassign posts to"
									chips
									hide-details
									hide-selected
									small-chips
									deletable-chips
								>
								</v-autocomplete><br />
								<v-btn @click="deleteUser()">
									Delete User <strong>&nbsp;{{ dialog_delete_user.username }}</strong>
								</v-btn>
						 </v-flex>
					 </v-layout>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_launch.show"
					width="500px"
					scrollable
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_launch.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Launch Site {{ dialog_launch.site.name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-layout row wrap>
						 <v-flex xs12 pa-2>
							<span>Will turn off search privacy and update development urls to the following live urls.</span><br /><br />
							<v-text-field label="Domain" prefix="https://" :value="dialog_launch.domain" @change.native="dialog_launch.domain = $event.target.value"></v-text-field>
							<v-btn @click="launchSite()">
								Launch Site
							</v-btn>
						 </v-flex>
					 </v-layout>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_captures.show"
					scrollable
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click="closeCaptures()">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Historical Captures of {{ dialog_captures.site.name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-toolbar color="grey lighten-4" light flat v-if="dialog_captures.captures.length > 0">
						<div style="max-width:250px;" class="mx-1 mt-8">
							<v-select v-model="dialog_captures.capture" dense :items="dialog_captures.captures" item-text="created_at_friendly" item-value="capture_id" label="Taken On" return-object @change="switchCapture"></v-select>
						</div>
						<div style="max-width:150px;" class="mx-1 mt-8">
							<v-select v-model="dialog_captures.selected_page" dense :items="dialog_captures.capture.pages" item-text="name" item-value="name" value="/" :label="`Contains ${dialog_captures.capture.pages.length} ${dialogCapturesPagesText}`" return-object></v-select>
						</div>
						<v-spacer></v-spacer>
						<v-toolbar-items>
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn text small @click="dialog_captures.show_configure = true" v-bind:class='{ "v-btn--active": dialog_bulk.show }' v-on="on"><small v-show="sites_selected.length > 0">({{ sites_selected.length }})</small><v-icon dark>mdi-settings</v-icon></v-btn>
							</template><span>Configure pages to capture</span>
						</v-tooltip>
						</v-toolbar-items>
					</v-toolbar>
					<v-card-text style="min-height:200px;">
					<v-card v-show="dialog_captures.show_configure" class="mt-5 mb-3" style="max-width:850px;margin:auto;">
						<v-toolbar color="grey lighten-4" dense light flat>
							<v-btn icon @click="dialog_captures.show_configure = false">
								<v-icon>close</v-icon>
							</v-btn>
							<v-toolbar-title>Configured pages to capture.</v-toolbar-title>
						</v-toolbar>
						<v-card-text>
							<v-alert type="info">Should start with a <code>/</code>. Example use <code>/</code> for the homepage and <code>/contact</code> for the the contact page.</v-alert>
								<v-text-field v-for="item in dialog_captures.pages" label="Page URL" :value="item.page" @change.native="item.page = $event.target.value"></v-text-field>
							<p><v-btn text small icon color="primary" @click="addAdditionalCapturePage"><v-icon>mdi-plus-box</v-icon></v-btn></p>
							<p><v-btn color="primary" @click="updateCapturePages()">Save Pages</v-btn></p>
						</v-card-text>
					</v-card>
					<v-container class="text-center" v-if="dialog_captures.captures.length > 0 && ! dialog_captures.loading">
						<img :src="`${dialog_captures.image_path}${dialog_captures.selected_page.image}` | safeUrl" style="max-width:100%;" class="elevation-5 mt-5">
					</v-container>
					<v-container v-show="dialog_captures.captures.length == 0 && ! dialog_captures.loading" class="mt-5">
						<v-alert type="info">There are no historical captures, yet.</v-alert>
					</v-container>
					<v-container v-show="dialog_captures.loading" class="mt-5">
						<v-progress-linear indeterminate rounded height="6" class="mb-3"></v-progress-linear>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_toggle.show"
					transition="dialog-bottom-transition"
					scrollable
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_toggle.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Toggle Site {{ dialog_toggle.site_name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-layout row wrap>
						 <v-flex xs6 pa-2>
							 <v-card>
								 <v-card-title primary-title>
									<div>
										<h3 class="headline mb-0">Deactivate Site</h3>
									</div>
								  </v-card-title>
									<v-card-text>
										<p>Will apply deactivate message with the following link back to the site owner.</p>
										<v-text-field label="Business Name" :value="dialog_toggle.business_name"></v-text-field>
										<v-text-field label="Business Link" :value="dialog_toggle.business_link"></v-text-field>
										<v-btn @click="DeactivateSite(dialog_toggle.site_id)">
											Deactivate Site
										</v-btn>
									</v-card-text>
							 </v-card>
						 </v-flex>
						 <v-flex xs6 pa-2>
							 <v-card>
								 <v-card-title primary-title>
									<div>
										<h3 class="headline mb-0">Activate Site</h3>
									</div>
								  </v-card-title>
									<v-card-text>
										<v-btn @click="ActivateSite(dialog_toggle.site_id)">
											Activate Site
										</v-btn>
									</v-card-text>
							 </v-card>
						 </v-flex>
					 </v-layout>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_migration.show"
					transition="dialog-bottom-transition"
					scrollable
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_migration.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Migrate from backup to {{ dialog_migration.site_name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
						<v-alert :value="true" type="info" color="yellow darken-4" class="mt-3">
							Warning {{ dialog_migration.site_name }} will be overwritten with backup. 
						</v-alert>
						<p></p>
						<v-form ref="formSiteMigration">
						<v-text-field :rules="[v => !!v || 'Backup URL is required']" required label="Backup URL" placeholder="https://storage.googleapis.com/..../live-backup.zip" :value="dialog_migration.backup_url" @change.native="dialog_migration.backup_url = $event.target.value"></v-text-field>
						<v-checkbox label="Update URLs" v-model="dialog_migration.update_urls" hint="Will change urls in database to match the existing site." persistent-hint></v-checkbox>
						<p></p>
						<v-btn @click="validateSiteMigration">
							Start Migration
						</v-btn>
						</v-form>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_copy_site.show"
					scrollable
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_copy_site.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Copy Site {{ dialog_copy_site.site.name }} to </v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-autocomplete
						:items="dialog_copy_site.options"
						v-model="dialog_copy_site.destination"
						label="Select Destination Site"
						item-text="name"
						item-value="id"
						chips
						small-chips
						deletable-chips
						></v-autocomplete>
						<v-btn @click="startCopySite()">
							Copy Site
						</v-btn>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_apply_https_urls.show"
					transition="dialog-bottom-transition"
					scrollable
					width="500"
				>
				<v-card tile>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_apply_https_urls.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Apply HTTPS Urls for {{ dialog_apply_https_urls.site_name }}</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
					<v-container>
						<v-alert :value="true" type="info" color="blue darken-3">
							Domain needs to match current home url. Otherwise server domain mapping will need updated to prevent redirection loop.
						</v-alert>
						<p></p>
						<span>Select url replacement option.</span><br />
						<v-btn @click="applyHttpsUrls( 'apply-https' )">
							Option 1: https://domain.tld
						</v-btn><br />
						<v-btn @click="applyHttpsUrls( 'apply-https-with-www' )">
							Option 2: https://www.domain.tld
						</v-btn>
					</v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
				<v-dialog
					v-model="dialog_file_diff.show"
					transition="dialog-bottom-transition"
					scrollable
				>
				<v-card>
					<v-toolbar flat dark color="primary">
						<v-btn icon dark @click.native="dialog_file_diff.show = false">
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>File diff {{ dialog_file_diff.file_name}}</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-toolbar-items class="hidden-sm-and-down">
							<v-btn text @click="QuicksaveFileRestore()">Restore this file</v-btn>
						</v-toolbar-items>
					</v-toolbar>
					<v-card-text>
						<v-container v-show="dialog_file_diff.loading"><v-progress-linear :indeterminate="true"></v-progress-linear></v-container>
						<v-container id="code_diff" v-html="dialog_file_diff.response" style='font-family:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;'></v-container>
					</v-card-text>
					</v-card>
				</v-dialog>
			<v-container fluid v-show="loading_page != true" style="padding:0px;">
			<v-card tile flat v-show="route == 'login'" class="mt-11">
				<v-card flat style="max-width:960px;margin: auto;margin-bottom:30px" v-if="fetchInvite.account">
				<v-alert type="info" style="border-radius: 4px;" elevation="2" dense color="primary" dark>
					To accept invitation either <strong>create new account</strong> or <strong>login</strong> to an existing account.
				</v-alert>
				<v-row>
				<v-col>
				<v-card tile style="max-width: 400px;margin: auto;">
					<v-toolbar color="grey lighten-4" light flat>
						<v-toolbar-title>Create new account</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text>
						<v-text-field readonly value="################" hint="Will use email where invite was sent to." persistent-hint label="Email" class="mt-3"></v-text-field>
						<v-text-field type="password" v-model="new_account.password" label="Password" class="mt-3"></v-text-field>
						<v-flex xs12>
							<v-btn color="primary" dark @click="createAccount()">Create Account</v-btn>
						</v-flex>
				</v-card-text>
				</v-card>
				</v-col>
				<v-col>
				<v-card tile style="max-width: 400px;margin: auto;">
					<v-toolbar color="grey lighten-4" light flat>
						<v-toolbar-title>Login</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text class="my-2">
					<v-form v-if="login.lost_password" ref="reset" @keyup.native.enter="resetPassword()">
					<v-row>
						<v-col cols="12">
							<v-text-field label="Username or Email" :value="login.user_login" @change.native="login.user_login = $event.target.value" required :disabled="login.loading" :rules="[v => !!v || 'Username is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-alert type="success" v-show="login.message">{{ login.message }}</v-alert>
						</v-col>
						<v-col cols="12">
							<v-progress-linear indeterminate rounded height="6" class="mb-3" v-show="login.loading"></v-progress-linear>
							<v-btn color="primary" @click="resetPassword()" :disabled="login.loading">Reset Password</v-btn>
						</v-col>
					</v-row>
					</v-form>
					<v-form lazy-validation ref="login" @keyup.native.enter="signIn()" v-else>
					<v-row>
						<v-col cols="12">
							<v-text-field label="Username or Email" :value="login.user_login" @change.native="login.user_login = $event.target.value" required :disabled="login.loading" :rules="[v => !!v || 'Username is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-text-field label="Password" :value="login.user_password" @change.native="login.user_password = $event.target.value" required :disabled="login.loading" type="password" :rules="[v => !!v || 'Password is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-alert type="error" v-show="login.errors">{{ login.errors }}</v-alert>
						</v-col>
						<v-col cols="12">
							<v-progress-linear indeterminate rounded height="6" class="mb-3" v-show="login.loading"></v-progress-linear>
							<v-btn color="primary" @click="signIn()" :disabled="login.loading">Login</v-btn>
						</v-col>
					</v-row>
					</v-form>
					</v-card-text>
				</v-card>
				<v-card tile flat style="max-width: 400px;margin: auto;" class="px-5">
					<a @click="login.lost_password = true" class="caption" v-show="!login.lost_password">Lost your password?</a>
					<a @click="login.lost_password = false" class="caption" v-show="login.lost_password">Back to login form.</a>
				</v-card>
				</v-col>
				</v-row>
			</v-card>
			<template v-else>
				<v-card tile style="max-width: 400px;margin: auto;">
					<v-toolbar color="grey lighten-4" light flat>
						<v-toolbar-title>Login</v-toolbar-title>
						<v-spacer></v-spacer>
					</v-toolbar>
					<v-card-text class="my-2">
					<v-form v-if="login.lost_password" @keyup.native.enter="resetPassword()" ref="reset">
					<v-row>
						<v-col cols="12">
							<v-text-field label="Username or Email" :value="login.user_login" @change.native="login.user_login = $event.target.value" required :disabled="login.loading" :rules="[v => !!v || 'Username is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-alert type="success" v-show="login.message">{{ login.message }}</v-alert>
						</v-col>
						<v-col cols="12">
							<v-progress-linear indeterminate rounded height="6" class="mb-3" v-show="login.loading"></v-progress-linear>
							<v-btn color="primary" @click="resetPassword()" :disabled="login.loading">Reset Password</v-btn>
						</v-col>
					</v-row>
					</v-form>
					<v-form lazy-validation ref="login" @keyup.native.enter="signIn()" v-else>
					<v-row>
						<v-col cols="12">
							<v-text-field label="Username or Email" :value="login.user_login" @change.native="login.user_login = $event.target.value" required :disabled="login.loading" :rules="[v => !!v || 'Username is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-text-field label="Password" :value="login.user_password" @change.native="login.user_password = $event.target.value" required :disabled="login.loading" type="password" :rules="[v => !!v || 'Password is required']"></v-text-field>
						</v-col>
						<v-col cols="12">
							<v-alert type="error" v-show="login.errors">{{ login.errors }}</v-alert>
						</v-col>
						<v-col cols="12">
							<v-progress-linear indeterminate rounded height="6" class="mb-3" v-show="login.loading"></v-progress-linear>
							<v-btn color="primary" @click="signIn()" :disabled="login.loading">Login</v-btn>
						</v-col>
					</v-row>
					</v-form>
					</v-card-text>
				</v-card>
				<v-card tile flat style="max-width: 400px;margin: auto;" class="px-5">
					<a @click="login.lost_password = true" class="caption" v-show="!login.lost_password">Lost your password?</a>
					<a @click="login.lost_password = false" class="caption" v-show="login.lost_password">Back to login form.</a>
				</v-card>
			</template>
			</v-card>
			<v-card tile v-if="route == 'sites'" id="sites" flat>
			<v-toolbar v-show="dialog_site.step == 1" id="site_listings" flat color="grey lighten-4">
				<v-toolbar-title>Listing {{ sites.length }} sites</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-tooltip top v-if="toggle_site == true">
							<template v-slot:activator="{ on }">
							<v-btn icon @click="toggle_site = false" v-on="on">
								<v-icon>image</v-icon>
							</v-btn>
							</template>
							<span>View as Thumbnails</span>
						</v-tooltip>
						<v-tooltip top v-if="toggle_site == false">
							<template v-slot:activator="{ on }">
							<v-btn icon @click="toggle_site = true" v-on="on">
								<v-icon>mdi-table</v-icon>
				</v-btn>
				</template>
							<span>View as List</span>
						</v-tooltip>
						<v-tooltip top>
					<template v-slot:activator="{ on }">
							<v-btn icon @click="view_console.show = !view_console.show" v-on="on">
								<v-icon>mdi-console</v-icon>
					</v-btn>
					</template>
							<span>Advanced Options</span>
				</v-tooltip>
						<v-btn v-if="role == 'administrator'" text @click="goToPath( `/account/sites/new` )">Add Site <v-icon dark>add</v-icon></v-btn>
						<v-btn v-else text @click="dialog_request_site.show = true; dialog_request_site.request.account_id = accounts[0].account_id">Add Site <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
			<v-sheet v-show="dialog_site.step == 1">
				<v-card-text v-show="requested_sites.length > 0">
				<v-dialog v-model="dialog_site_request.show" width="500">
				<v-card>
					<v-card-title class="headline grey lighten-2">
					Update site request
					</v-card-title>

					<v-card-text>
						<v-text-field label="New Site URL" v-model="dialog_site_request.request.url"></v-text-field>
						<v-text-field label="Name" v-model="dialog_site_request.request.name"></v-text-field>
						<v-textarea label="Notes" v-model="dialog_site_request.request.notes"></v-textarea>
					</v-card-text>
					<v-divider></v-divider>
					<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn @click="dialog_site_request.show = false">Cancel</v-btn>
					<v-btn color="primary" @click="updateRequestSite">Save</v-btn>
					</v-card-actions>
				</v-card>
				</v-dialog>
				<v-stepper :value="request.step" v-for="(request, index) in requested_sites" class="mb-3">
				<v-toolbar flat dense class="primary white--text">
						<div v-if="role == 'administrator'">Requested by {{ user_name( request.user_id ) }} -&nbsp;</div><strong>{{ request.name }}</strong>&nbsp;in {{ account_name( request.account_id ) }}
					<v-spacer></v-spacer>
						<v-btn small @click="modifyRequest( index )" v-show="role == 'administrator'" class="mx-1">Modify</v-btn>
						<v-btn small @click="finishRequest( index )" v-if="request.step == 3" class="mx-1">Finish</v-btn>
						<v-btn small @click="cancelRequest( index )" v-else class="mx-1">Cancel</v-btn>
				</v-toolbar>
				<v-stepper-header class="elevation-0">
						<v-stepper-step step="1" :complete="request.step > 0">Requesting site<small>{{ request.created_at | pretty_timestamp_epoch }}</small></v-stepper-step>
				<v-divider></v-divider>
						<v-stepper-step step="2" :complete="request.step > 1">Preparing new site<small v-show="request.processing_at">{{ request.processing_at | pretty_timestamp_epoch }}</small></v-stepper-step>
				<v-divider></v-divider>
						<v-stepper-step step="3" :complete="request.step > 2">Ready to use<small v-show="request.ready_at">{{ request.ready_at | pretty_timestamp_epoch }}</small></v-stepper-step>
				</v-stepper-header>
					<v-stepper-items>
					<v-stepper-content step="1">
					<div>{{ request.notes }}</div>
						<v-btn color="primary" @click="continueRequestSite( request )" v-show="role == 'administrator'">
							Continue
						</v-btn>
					</v-stepper-content>
					<v-stepper-content step="2">
					<div v-show="role == 'administrator'">
						<v-btn @click="backRequestSite( request )">
							Back
						</v-btn>
						<v-btn color="primary" @click="continueRequestSite( request )">
							Continue
						</v-btn>
					</div>
					</v-stepper-content>
					<v-stepper-content step="3">
						<v-card v-if="typeof request.url == 'string' && request.url != ''" class="elevation-2 ma-2">
						<v-list dense>
							<v-list-item :href="request.url" target="_blank" dense>
							<v-list-item-content>
								<v-list-item-title>Link</v-list-item-title>
								<v-list-item-subtitle v-text="request.url"></v-list-item-subtitle>
							</v-list-item-content>
							<v-list-item-icon>
								<v-icon>mdi-open-in-new</v-icon>
							</v-list-item-icon>
							</v-list-item>
						</v-list>
						</v-card>
					<div v-show="role == 'administrator'">
						<v-btn @click="backRequestSite( request )">
							Back
						</v-btn>
						<v-btn color="primary" @click="continueRequestSite( request )">
							Continue
						</v-btn>
					</div>
					</v-stepper-content>
					</v-stepper-items>
			</v-stepper>
			</v-card-text>
				<v-data-table
					v-model="sites_selected"
					:headers="[
						{ text: '', width: 30, value: 'thumbnail' },
						{ text: 'Name', align: 'left', sortable: true, value: 'name' },
						{ text: 'Subsites', value: 'subsites', width: 104 },
						{ text: 'WordPress', value: 'core', width: 114 },
						{ text: 'Visits', value: 'visits', width: 98 },
						{ text: 'Storage', value: 'storage', width: 98 },
						{ text: 'Provider', value: 'provider', width: 104 },
						{ text: '', value: 'filtered', width: 0, class: 'hidden', filter: filteredSites }
					]"
					:items="sites"
					:search="search"
					item-key="site_id"
					ref="site_datatable"
					:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
					v-if="toggle_site"
				>
				<template v-slot:top>
				<v-row>
					<v-col></v-col>
					<v-col cols="12" md="4">
						<v-text-field class="mx-4" v-model="search" @input="filterSites" autofocus label="Search" clearable light hide-details append-icon="search"></v-text-field>	
					</v-col>
				</v-row>	
				</template>
				<template v-slot:body="{ items }">
					<tbody>
					<tr v-for="item in items" :key="item.site_id" @click="goToPath( `/account/sites/${item.site_id}` )" style="cursor:pointer;">
						<td>
							<v-img :src=`${remote_upload_uri}${item.site}_${item.site_id}/production/screenshots/${item.screenshot_base}_thumb-100.jpg` class="elevation-1" width="50" v-show="item.screenshot_base"></v-img>
						</td>
						<td>{{ item.name }}</td>
						<td>{{ item.subsites }}<span v-show="items.subsites"> sites</span></td>
						<td>{{ item.core }}</td>
						<td>{{ item.visits | formatLargeNumbers }}</td>
						<td>{{ item.storage | formatGBs }}GB</td>
						<td>{{ item.provider | formatProvider }}</td>
					</tr>
					</tbody>
				</template>
				</v-data-table>
				<v-data-table
					v-model="sites_selected"
					:headers="[
						{ text: '', width: 30, value: 'thumbnail' },
						{ text: 'Name', align: 'left', sortable: true, value: 'name' },
						{ text: 'Subsites', value: 'subsites', width: 104 },
						{ text: 'WordPress', value: 'core', width: 114 },
						{ text: 'Visits', value: 'visits', width: 98 },
						{ text: 'Storage', value: 'storage', width: 98 },
						{ text: 'Provider', value: 'provider', width: 104 },
						{ text: '', value: 'filtered', width: 0, class: 'hidden', filter: filteredSites }
					]"
					:items="sites"
					:search="search"
					item-key="site_id"
					ref="site_datatable"
					hide-default-header
					:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
					v-else
				>
				<template v-slot:top>
				<v-row>
					<v-col></v-col>
					<v-col cols="12" md="4">
						<v-text-field class="mx-4" v-model="search" @input="filterSites" autofocus label="Search" clearable light hide-details append-icon="search"></v-text-field>	
					</v-col>
				</v-row>	
				</template>
				<template v-slot:body="{ items }">
					<tbody>
					<tr class="v-data-table__empty-wrapper">
						<td colspan="9">
						<v-row>
						<v-col cols="12">
						<v-card flat>
							<v-container fluid>
							<v-row>
								<v-col
								v-for="item in items"
								:key="item.site_id"
								class="d-flex child-flex"
								cols="12"
								sm="4"
								>
								<v-card tile style="cursor: pointer" @click="goToPath( `/account/sites/${item.site_id}` )">
									<v-img :src=`${remote_upload_uri}${item.site}_${item.site_id}/production/screenshots/${item.screenshot_base}_thumb-800.jpg` :aspect-ratio="8/5" v-show="item.screenshot_base">
									<v-row align="end" class="lightbox white--text pa-2 fill-height">
									<v-col class="pa-1">
										<div class="body-1">{{ item.name }}</div>
									</v-col>
									</v-row>
									<template v-slot:placeholder>
										<v-row
										class="fill-height ma-0"
										align="center"
										justify="center"
										>
										<v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
										</v-row>
									</template>
									</v-img>
								</v-card>
								</v-col>
							</v-row>
							</v-container>
						</v-card>
						</v-col>
					</v-row>
						</td>
					</tr>
					</tbody>
				</template>
				</v-data-table>
			</v-sheet>
			<v-sheet v-show="dialog_site.step == 2" class="site">
			<v-card flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-img :src=`${remote_upload_uri}${dialog_site.site.site}_${dialog_site.site.site_id}/production/screenshots/${dialog_site.site.screenshot_base}_thumb-100.jpg` class="elevation-1 mr-3" max-width="50" v-show="dialog_site.site.screenshot_base"></v-img>
					<v-toolbar-title>
					<v-autocomplete
						v-model="dialog_site.site"
						:items="sites"
						return-object
						item-text="name"
						@input="goToPath( `/account/sites/${dialog_site.site.site_id}` )"
						class="mt-5"
						spellcheck="false"
						flat
					>
					</v-autocomplete>
					</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-tooltip>
							<template v-slot:activator="{ on }">
								<v-btn text small @click="showLogEntry(dialog_site.site.site_id)" v-on="on" v-show="role == 'administrator'"><v-icon dark>mdi-check</v-icon></v-btn>
							</template>
							<span>Add Log Entry</span>
						</v-tooltip>
						<v-divider vertical class="mx-1" inset v-show="role == 'administrator'"></v-divider>
						<v-btn text href="/account/sites" @click.prevent="goToPath( '/account/sites' )"><v-icon>mdi-arrow-left</v-icon> Back</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-tabs v-model="dialog_site.site.tabs" background-color="primary" dark>
					<v-tab :key="1" href="#tab-Site-Management">
						Site Management <v-icon size="24">mdi-settings</v-icon>
					</v-tab>
					<v-tab :key="8" href="#tab-Timeline" ripple @click="fetchTimeline( dialog_site.site.site_id )">
						Timeline <v-icon size="24">mdi-timeline-text-outline</v-icon>
					</v-tab>
				</v-tabs>
				<v-tabs-items v-model="dialog_site.site.tabs">
					<v-tab-item value="tab-Site-Management" :transition="false" :reverse-transition="false">
						<div class="grey lighten-4 pb-2">
						<v-layout wrap>
							<v-flex sx12 sm4 px-2>
							<v-layout>
							<v-flex style="width:180px;">
								<v-select
									v-model="dialog_site.environment_selected"
									:items="dialog_site.site.environments"
									return-object
									item-text="environment_label"
									@input="triggerEnvironmentUpdate"
									style="height:54px;">
								</v-select>
								</v-flex>
								<v-flex>
								<v-tooltip bottom>
									<template v-slot:activator="{ on }">
									<v-btn small icon @click="syncSite()" style="margin: 12px auto 0 0;" v-on="on">
										<v-icon color="grey">mdi-sync</v-icon>
									</v-btn>
									</template>
									<span>Manual sync website details. Last sync {{ dialog_site.site.updated_at | timeago }}.</span>
								</v-tooltip>
									</v-flex>
									</v-layout>
									</v-flex>
									<v-flex xs12 sm8>
           							 	<v-tabs v-model="dialog_site.site.tabs_management" background-color="grey lighten-4" icons-and-text right show-arrows height="54">
										<v-tab key="Info" href="#tab-Info">
											Info <v-icon>mdi-library-books</v-icon>
										</v-tab>
           							 	<v-tab key="Stats" href="#tab-Stats" @click="fetchStats()">
											Stats <v-icon>mdi-chart-bar</v-icon>
										</v-tab>
										<v-tab key="Plugins" href="#tab-Addons">
											Addons <v-icon>mdi-power-plug</v-icon>
										</v-tab>
										<v-tab key="Users" href="#tab-Users" @click="fetchUsers()">
											Users <v-icon>mdi-account-multiple</v-icon>
										</v-tab>
										<v-tab key="Updates" href="#tab-Updates" @click="fetchUpdateLogs( dialog_site.site.site_id )">
											Updates <v-icon>mdi-book-open</v-icon>
										</v-tab>
										<v-tab key="Scripts" href="#tab-Scripts">
											Scripts <v-icon>mdi-code-tags</v-icon>
										</v-tab>
										<v-tab key="Backups" href="#tab-Backups" @click="dialog_site.backup_step = 1">
											Backups <v-icon>mdi-update</v-icon>
										</v-tab>
									</v-tabs>
									</v-flex>
									</v-layout>
								</div>
        		<v-tabs-items v-model="dialog_site.site.tabs_management" v-if="dialog_site.loading != true">
					<v-tab-item :key="1" value="tab-Info" :transition="false" :reverse-transition="false">
						<v-toolbar color="grey lighten-4" dense light flat>
							<v-toolbar-title>Info</v-toolbar-title>
							<v-spacer></v-spacer>
						</v-toolbar>
               			 <v-card flat>
							<v-container fluid>
							<v-layout body-1 px-6 class="row">
								<v-flex xs12 md6 class="py-2">
								<div class="block mt-6">
                            		<a @click="showCaptures( dialog_site.site.site_id )"><v-img :src="dialog_site.environment_selected.screenshots.large" max-width="400" aspect-ratio="1.6" class="elevation-5" v-show="dialog_site.environment_selected.screenshots.large" style="margin:auto;"></v-img></a>
								</div>
								<v-list dense style="padding:0px;max-width:350px;margin: auto;" class="mt-6">
									<v-list-item :href="dialog_site.environment_selected.link" target="_blank" dense>
									<v-list-item-content>
										<v-list-item-title>Link</v-list-item-title>
										<v-list-item-subtitle v-text="dialog_site.environment_selected.link"></v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-open-in-new</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copySFTP( dialog_site.environment_selected )" dense>
									<v-list-item-content>
										<v-list-item-title>SFTP Info</v-list-item-title>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copyDatabase( dialog_site.environment_selected )" dense v-if="dialog_site.environment_selected.database">
									<v-list-item-content>
										<v-list-item-title>Database Info</v-list-item-title>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="viewMailgunLogs()" dense v-if="dialog_site.site.mailgun">
									<v-list-item-content>
										<v-list-item-title>Mailgun Logs</v-list-item-title>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>email</v-icon>
									</v-list-item-icon>
									</v-list-item>
								</v-list>
								</v-flex>
								<v-flex xs12 md6 class="keys py-2">
								<v-list dense style="padding:0px;max-width:350px;margin: auto;">
									<v-list-item @click="copyText( dialog_site.environment_selected.address )" dense>
									<v-list-item-content>
										<v-list-item-title>Address</v-list-item-title>
										<v-list-item-subtitle v-text="dialog_site.environment_selected.address"></v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copyText( dialog_site.environment_selected.username )" dense>
									<v-list-item-content>
										<v-list-item-title>Username</v-list-item-title>
										<v-list-item-subtitle v-text="dialog_site.environment_selected.username"></v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copyText( dialog_site.environment_selected.password )" dense>
									<v-list-item-content>
										<v-list-item-title>Password</v-list-item-title>
										<v-list-item-subtitle>##########</v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copyText( dialog_site.environment_selected.protocol )" dense>
									<v-list-item-content>
										<v-list-item-title>Protocol</v-list-item-title>
										<v-list-item-subtitle v-text="dialog_site.environment_selected.protocol"></v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<v-list-item @click="copyText( dialog_site.environment_selected.port )" dense>
									<v-list-item-content>
										<v-list-item-title>Port</v-list-item-title>
										<v-list-item-subtitle v-text="dialog_site.environment_selected.port"></v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-icon>
										<v-icon>mdi-content-copy</v-icon>
									</v-list-item-icon>
									</v-list-item>
									<div v-if="dialog_site.environment_selected.database">
										<v-list-item :href="dialog_site.environment_selected.database" target="_blank" dense>
										<v-list-item-content>
											<v-list-item-title>Database</v-list-item-title>
											<v-list-item-subtitle v-text="dialog_site.environment_selected.database"></v-list-item-subtitle>
										</v-list-item-content>
										<v-list-item-icon>
											<v-icon>mdi-open-in-new</v-icon>
										</v-list-item-icon>
										</v-list-item>
										<v-list-item @click="copyText( dialog_site.environment_selected.database_name )" dense>
										<v-list-item-content>
											<v-list-item-title>Database Name</v-list-item-title>
											<v-list-item-subtitle v-text="dialog_site.environment_selected.database_name"></v-list-item-subtitle>
										</v-list-item-content>
										<v-list-item-icon>
											<v-icon>mdi-content-copy</v-icon>
										</v-list-item-icon>
										</v-list-item>
										<v-list-item @click="copyText( dialog_site.environment_selected.database_username )" dense>
										<v-list-item-content>
											<v-list-item-title>Database Username</v-list-item-title>
											<v-list-item-subtitle v-text="dialog_site.environment_selected.database_username"></v-list-item-subtitle>
										</v-list-item-content>
										<v-list-item-icon>
											<v-icon>mdi-content-copy</v-icon>
										</v-list-item-icon>
										</v-list-item>
										<v-list-item @click="copyText( dialog_site.environment_selected.database_password )" dense>
										<v-list-item-content>
											<v-list-item-title>Database Password</v-list-item-title>
											<v-list-item-subtitle>##########</v-list-item-subtitle>
										</v-list-item-content>
										<v-list-item-icon>
											<v-icon>mdi-content-copy</v-icon>
										</v-list-item-icon>
										</v-list-item>
									</div>
									<div v-if="dialog_site.environment_selected.ssh">
										<v-list-item @click="copyText( dialog_site.environment_selected.ssh )" dense>
										<v-list-item-content>
											<v-list-item-title>SSH Connection</v-list-item-title>
											<v-list-item-subtitle v-text="dialog_site.environment_selected.ssh"></v-list-item-subtitle>
										</v-list-item-content>
										<v-list-item-icon>
											<v-icon>mdi-content-copy</v-icon>
										</v-list-item-icon>
										</v-list-item>
									</div>
								</v-list>
							</v-flex>
						</v-layout>
						</v-container>
					<v-divider></v-divider>
					<v-subheader>Site Options</v-subheader>
					<v-container>
					<v-btn small depressed @click="PushProductionToStaging( dialog_site.site.site_id )" v-show="dialog_site.site.provider == 'kinsta'">
						<v-icon>local_shipping</v-icon> Push Production to Staging
					</v-btn>
					<v-btn small depressed @click="PushStagingToProduction( dialog_site.site.site_id )" v-show="dialog_site.site.provider == 'kinsta'">
						<v-icon class="reverse">local_shipping</v-icon> Push Staging to Production
					</v-btn>
					<v-btn small depressed @click="dialog_mailgun_config.show = true" v-show="role == 'administrator'">
						<v-icon>mdi-email-search</v-icon> Configure Mailgun
					</v-btn>
					<v-btn small depressed @click="copySite(dialog_site.site.site_id)">
						<v-icon>file_copy</v-icon> Copy Site
					</v-btn>
					</v-container>
					<div v-show="role == 'administrator'">
					<v-divider></v-divider>
					<v-subheader>Administrator Options</v-subheader>
					<v-container>
					<v-btn small depressed @click="editSite()">
						<v-icon>edit</v-icon> Edit Site
					</v-btn>
					<v-btn small depressed color="error" @click="deleteSite(dialog_site.site.site_id)">
						<v-icon>delete</v-icon> Delete Site
					</v-btn>
					</v-container>
					</div>
					<v-layout class="mb-10"></v-layout>
				</v-tab-item>
				<v-tab-item :key="100" value="tab-Stats" :transition="false" :reverse-transition="false">
					<v-card flat>
					<v-toolbar color="grey lighten-4" dense light flat>
						<v-toolbar-title>Stats</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-toolbar-items v-if="typeof dialog_new_site == 'object'" v-show="role == 'administrator'">
                    		<v-btn text @click="configureFathom( dialog_site.site.site_id )">Configure Fathom Tracker <v-icon dark small>bar_chart</v-icon></v-btn>
						</v-toolbar-items>
					</v-toolbar>
						<div class="pa-3" v-if="typeof dialog_site.environment_selected.stats == 'string' && dialog_site.environment_selected.stats != 'Loading'">
							{{ dialog_site.environment_selected.stats }}
						</div>
						<v-layout wrap>
						<v-flex xs12>
						<v-card-text v-show="dialog_site.environment_selected.stats == 'Loading'">
							<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
						</v-card-text>
						<div v-for="e in dialog_site.site.environments" v-show="e.environment == dialog_site.environment_selected.environment">
							<div :id="`chart_` + dialog_site.site.site_id + `_` + e.environment"></div>
							<v-card flat v-if="dialog_site.environment_selected.stats && dialog_site.environment_selected.stats.agg">
							<v-card-title class="text-center pa-0">
							<v-layout wrap>
							<v-flex xs6 sm3>
								<span class="text-uppercase caption">Unique Visitors</span><br />
								<span class="display-1 font-weight-thin text-uppercase">{{ dialog_site.environment_selected.stats.agg.Visitors | formatk }}</span>
							</v-flex>
							<v-flex xs6 sm3>
								<span class="text-uppercase caption">Pageviews</span><br />
								<span class="display-1 font-weight-thin text-uppercase">{{ dialog_site.environment_selected.stats.agg.Pageviews | formatk }}</span>
							</v-flex>
							<v-flex xs6 sm3>
								<span class="text-uppercase caption">Avg Time On Site</span><br />
								<span class="display-1 font-weight-thin text-uppercase">{{ dialog_site.environment_selected.stats.agg.AvgDuration | formatTime }}</span>
							</v-flex>
							<v-flex xs6 sm3>
								<span class="text-uppercase caption">Bounce Rate</span><br />
								<span class="display-1 font-weight-thin text-uppercase">{{ dialog_site.environment_selected.stats.agg.BounceRate | formatPercentageFixed }}</span>
							</v-flex>
							</v-layout>
							</v-card-title>
							</v-card>
							<v-card flat class="mb-3">
							<v-card-title>
							<v-layout wrap v-show="dialog_site.environment_selected.stats.pages">
							<v-flex xs12 sm6 pr-2>
							<v-data-table
								:headers='[{"text":"Top Pages","value":"page",sortable: false, class: "text-truncate"},{"text":"Views","value":"views",sortable: false, "width": 90, align: "right"},{"text":"Uniques","value":"uniques",sortable: false, "width": 98, align: "right"}]'
								:items="dialog_site.environment_selected.stats.pages"
								class="elevation-0 table-layout-fixed"
								hide-default-footer
							>
								<template v-slot:body="{ items }">
								<tbody>
									<tr v-for="item in items">
										<td class="text-truncate"><a :href="item.Hostname + item.Pathname" target="_blank" class="text-truncate">{{ item.Pathname }}</a></td>
										<td class="text-right">{{ item.Pageviews | formatk }}</td>
										<td class="text-right">{{ item.Visitors | formatk }}</td>
									</tr>
								</tbody>
								</template>
							</v-data-table>
							</v-flex>
							<v-flex xs12 sm6 pl-2>
							<v-data-table
								:headers='[{"text":"Top Referrers","value":"referrer", sortable: false, align: "truncate"},{"text":"Views", "value":"views" ,sortable: false, "width": 90, align: "right"},{"text":"Uniques","value":"uniques", sortable: false, "width": 98, align: "right"}]'
								:items="dialog_site.environment_selected.stats.referrers"
								class="elevation-0 table-layout-fixed"
								hide-default-footer
							>
								<template v-slot:body="{ items }">
								<tbody>
									<tr v-for="item in items">
										<td class="text-truncate"><a :href="item.Hostname + item.Pathname" target="_blank">{{ item.Group || item.Hostname + item.Pathname }}</a></td>
										<td class="text-right">{{ item.Pageviews | formatk }}</td>
										<td class="text-right">{{ item.Visitors | formatk }}</td>
									</tr>
								</tbody>
								</template>
							</v-data-table>
							</v-flex>
							</v-layout>
							</v-card-title>
							</v-card>
						</div>
						</v-flex>
					</v-card>
				</v-tab-item>
				<v-tab-item :key="3" value="tab-Addons" :transition="false" :reverse-transition="false">
					<v-card flat>
					<v-toolbar color="grey lighten-4" dense light flat>
						<v-toolbar-title>Addons <small>(Themes/Plugins)</small></v-toolbar-title>
						<v-spacer></v-spacer>
						<v-toolbar-items>
							<v-btn text @click="bulkEdit(dialog_site.site.site_id, 'plugins')" v-if="dialog_site.environment_selected.plugins_selected.length != 0">Bulk Edit {{ dialog_site.environment_selected.plugins_selected.length }} plugins</v-btn>
							<v-btn text @click="bulkEdit(dialog_site.site.site_id, 'themes')" v-if="dialog_site.environment_selected.themes_selected.length != 0">Bulk Edit {{ dialog_site.environment_selected.themes_selected.length }} themes</v-btn>
                    <v-btn text @click="addTheme(dialog_site.site.site_id)">Add Theme <v-icon dark small>add</v-icon></v-btn>
                    <v-btn text @click="addPlugin(dialog_site.site.site_id)">Add Plugin <v-icon dark small>add</v-icon></v-btn>
						</v-toolbar-items>
					</v-toolbar>
					<v-card-title v-if="typeof dialog_site.environment_selected.themes == 'string'">
					<div>
						Updating themes...
						<v-progress-linear :indeterminate="true"></v-progress-linear>
					</div>
					</v-card-title>
					<div v-else>
					<v-subheader>Themes</v-subheader>
					<v-data-table
						v-model="dialog_site.environment_selected.themes_selected"
						:headers="header_themes"
						:items="dialog_site.environment_selected.themes"
						:loading="dialog_site.site.loading_themes"
						:items-per-page="-1"
						:footer-props="{ itemsPerPageOptions: [{'text':'All','value':-1}] }"
						item-key="name"
						value="name"
						show-select
						hide-default-footer
						>
						<template v-slot:item.status="{ item }">
							<div v-if="item.status === 'inactive' || item.status === 'parent' || item.status === 'child'">
                        		<v-switch hide-details v-model="item.status" false-value="inactive" true-value="active" @change="activateTheme( item.name, dialog_site.site.site_id )"></v-switch>
							</div>
							<div v-else>
								{{ item.status }}
							</div>
						</template>
						<template v-slot:item.actions="{ item }" class="text-center px-0">
                    <v-btn icon small class="mx-0" @click="deleteTheme(item.name, dialog_site.site.site_id)">
								<v-icon small color="pink">delete</v-icon>
							</v-btn>
						</template>
					</v-data-table>
				</div>
					<v-card-title v-if="typeof dialog_site.environment_selected.plugins == 'string'">
						<div>
							Updating plugins...
							<v-progress-linear :indeterminate="true"></v-progress-linear>
						</div>
					</v-card-title>
					<div v-else>
					<v-subheader>Plugins</v-subheader>
					<v-data-table
						:headers="header_plugins"
						:items="dialog_site.environment_selected.plugins.filter(plugin => plugin.status != 'must-use' && plugin.status != 'dropin')"
						:loading="dialog_site.site.loading_plugins"
						:items-per-page="-1"
						:footer-props="{ itemsPerPageOptions: [{'text':'All','value':-1}] }"
						v-model="dialog_site.environment_selected.plugins_selected"
						item-key="name"
						value="name"
						show-select
						hide-default-footer
					>
					<template v-slot:item.status="{ item }">
						<div v-if="item.status === 'inactive' || item.status === 'active'">
                <v-switch hide-details v-model="item.status" false-value="inactive" true-value="active" @change="togglePlugin(item.name, item.status, dialog_site.site.site_id)"></v-switch>
						</div>
						<div v-else>
							{{ item.status }}
						</div>
					</template>
					<template v-slot:item.actions="{ item }" class="text-center px-0">
                <v-btn icon small class="mx-0" @click="deletePlugin(item.name, dialog_site.site.site_id)" v-if="item.status === 'active' || item.status === 'inactive'">
							<v-icon small color="pink">delete</v-icon>
						</v-btn>
					</template>
					<template v-slot:body.append>
						<tr v-for="plugin in dialog_site.environment_selected.plugins.filter(plugin => plugin.status == 'must-use' || plugin.status == 'dropin')">
							<td></td>
							<td>{{ plugin.title }}</td>
							<td>{{ plugin.name }}</td>
							<td>{{ plugin.version }}</td>
							<td>{{ plugin.status }}</td>
							<td class="text-center px-0"></td>
						</tr>
					</template>
					</v-data-table>
				</div>
			</v-tab-item>
			<v-tab-item :key="4" value="tab-Users" :transition="false" :reverse-transition="false">
				<v-card flat>
				<v-toolbar color="grey lighten-4" dense light flat>
					<v-toolbar-title>Users</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-text-field
						v-model="users_search"
						ref="users_search"
						append-icon="search"
						label="Search"
						single-line
						clearable
						hide-details
						style="max-width:300px"
					></v-text-field>
					<v-toolbar-items>
                <v-btn text @click="bulkEdit(dialog_site.site.site_id,'users')" v-if="dialog_site.environment_selected.users_selected.length != 0">Bulk Edit {{ dialog_site.environment_selected.users_selected.length }} users</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text v-show="typeof dialog_site.environment_selected.users == 'string'">
					<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
				</v-card-text>
					<div v-if="typeof dialog_site.environment_selected.users != 'string'">
						<v-data-table
							:headers='header_users'
							:items-per-page="50"
							:footer-props="{ itemsPerPageOptions: [50,100,250,{'text':'All','value':-1}] }"
							:items="dialog_site.environment_selected.users"
							item-key="user_login"
							v-model="dialog_site.environment_selected.users_selected"
							class="table_users"
							:search="users_search"
							show-select
						>
						<template v-slot:item.roles="{ item }">
							{{ item.roles.split(",").join(" ") }}
						</template>
						<template v-slot:item.actions="{ item }">
                    <v-btn small rounded @click="loginSite(dialog_site.site.site_id, item.user_login)" class="my-2">Login as</v-btn>
                    <v-btn icon small class="my-2" @click="deleteUserDialog( item.user_login, dialog_site.site.site_id)">
							<v-icon small color="pink">delete</v-icon>
						</v-btn>
						</template>
					  </v-data-table>
					</div>
				</v-card>
			</v-tab-item>
			<v-tab-item :key="5" value="tab-Updates" :transition="false" :reverse-transition="false">
				<v-toolbar color="grey lighten-4" dense light flat>
					<v-toolbar-title>Update Logs</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
                <v-btn text @click="runUpdate(dialog_site.site.site_id)">Manual update <v-icon dark>mdi-sync</v-icon></v-btn>
                <v-btn text @click="updateSettings(dialog_site.site.site_id)">Update Settings <v-icon dark>mdi-settings</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card flat>
					<v-card-text v-show="typeof dialog_site.environment_selected.update_logs == 'string'">
						<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
					</v-card-text>
					<div v-if="typeof dialog_site.environment_selected.update_logs != 'string'">
							<v-data-table
								:headers='header_updatelog'
								:items="dialog_site.environment_selected.update_logs"
								class="update_logs"
								:footer-props="{ itemsPerPageOptions: [50,100,250,{'text':'All','value':-1}] }"
							>
						    <template v-slot:body="{ items }">
							<tbody>
							<tr v-for="item in items">
								<td>{{ item.created_at | pretty_timestamp_epoch }}</td>
								<td>{{ item.type }}</td>
								<td>{{ item.name }}</td>
								<td class="text-right">{{ item.old_version }}</td>
								<td class="text-right">{{ item.new_version }}</td>
								<td>{{ item.status }}</td>
							</tr>
							</tbody>
						    </template>
						  </v-data-table>
						</div>
				</v-card>
			</v-tab-item>
			<v-tab-item :key="6" value="tab-Scripts" :transition="false" :reverse-transition="false">
				<v-toolbar color="grey lighten-4" dense light flat>
					<v-toolbar-title>Scripts</v-toolbar-title>
					<v-spacer></v-spacer>
				</v-toolbar>
				<v-card flat>
					<v-card-title>
					<v-row>
					<v-col cols="12" md="8">
					<v-subheader id="script_site">Custom bash script or WP-CLI commands</v-subheader>
						<v-textarea
							auto-grow
							solo
							label=""
							hide-details
							:value="custom_script" 
							@change.native="custom_script = $event.target.value"
							spellcheck="false"
							class="code"
						></v-textarea>
                <v-btn small color="primary" dark @click="runCustomCode(dialog_site.site.site_id)">Run Custom Code</v-btn>
					</v-col>
					<v-col cols="12" md="4">
						<v-list dense>
						<v-subheader>Common</v-subheader>
						<v-list-item @click="viewApplyHttpsUrls(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>launch</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Apply HTTPS Urls</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-list-item @click="siteDeploy(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>loop</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Deploy Defaults</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-list-item @click="launchSiteDialog(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>mdi-rocket</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Launch Site</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-list-item @click="showSiteMigration(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>mdi-truck</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Migrate from backup</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-list-item @click="resetPermissions(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>mdi-file-lock</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Reset Permissions</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-list-item @click="toggleSite(dialog_site.site.site_id)" dense>
						<v-list-item-icon>
							<v-icon>mdi-toggle-switch</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>Toggle Site</v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-subheader v-show="recipes.filter( r => r.public == 1 ).length > 0">Other</v-subheader>
						<v-list-item @click="runRecipe( recipe.recipe_id, dialog_site.site.site_id )" dense v-for="recipe in recipes.filter( r => r.public == 1 )">
						<v-list-item-icon>
							<v-icon>mdi-script-text-outline</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title v-text="recipe.title"></v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						<v-subheader v-show="recipes.filter( r => r.public != 1 ).length > 0">User</v-subheader>
						<v-list-item @click="loadRecipe( recipe.recipe_id ); $vuetify.goTo( '#script_site' );" dense v-for="recipe in recipes.filter( r => r.public != 1 )">
						<v-list-item-icon>
							<v-icon>mdi-script-text-outline</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title v-text="recipe.title"></v-list-item-title>
						</v-list-item-content>
						</v-list-item>
						</v-list>
					</v-col>
					</v-row>
					</v-card-title>
				</v-card>
			</v-tab-item>
			<v-tab-item :key="7" value="tab-Backups" :transition="false" :reverse-transition="false">
				<v-toolbar color="grey lighten-4" dense light flat>
					<v-toolbar-title>Backups</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-tooltip top>
							<template v-slot:activator="{ on }">
                        <v-btn text small @click="promptBackupSnapshot( dialog_site.site.site_id )" v-on="on"><v-icon dark>mdi-cloud-download</v-icon></v-btn>
							</template><span>Generate and Download Snapshot</span>
						</v-tooltip>
						<v-divider vertical class="mx-1" inset></v-divider>
						<v-tooltip top>
							<template v-slot:activator="{ on }">
                    		<v-btn text @click="QuicksaveCheck( dialog_site.site.site_id )" v-on="on"><v-icon dark>mdi-sync</v-icon></v-btn>
							</template><span>Manual check for new Quicksave</span>
						</v-tooltip>
					</v-toolbar-items>
				</v-toolbar>
				<v-sheet v-show="dialog_site.backup_step == 1" class="mt-7">
				  <v-card flat>
				<v-row class="px-5">
				<v-col cols="12" md="4" class="px-2">
				<v-card
					class="mx-auto"
					max-width="344"
					outlined
					link
					hover
					@click="viewBackups(); dialog_site.backup_step = 2"
				>
					<v-card-title>Backups</v-card-title>
					<v-card-subtitle>Original file and database backups.</v-card-subtitle>
					<v-card-text>
						<span v-if="typeof dialog_site.environment_selected.details.backup_count == 'number'">{{ dialog_site.environment_selected.details.backup_count }} backups</v-show>
					</v-card-text>
				</v-card>
				</v-col>
				<v-col cols="12" md="4" class="px-2">
				<v-card
					class="mx-auto"
					max-width="344"
					outlined
					link
					hover
					@click="viewQuicksaves(); dialog_site.backup_step = 3"
				>
					<v-card-title>Quicksaves</v-card-title>
					<v-card-subtitle>Know what changed and when. Easily rollback themes or plugins. Super helpful for troubleshooting maintenance issues.</v-card-subtitle>
					<v-card-text>
						<span v-if="typeof dialog_site.environment_selected.details.quicksave_usage == 'object'">{{ dialog_site.environment_selected.details.quicksave_usage.count }} quicksaves</v-show>
					</v-card-text>
				</v-card>
				</v-col>
				<v-col cols="12" md="4" class="px-2">
				<v-card
					class="mx-auto"
					max-width="344"
					outlined
					link
					hover
					@click="viewSnapshots( dialog_site.site.site_id ); dialog_site.backup_step = 4"
				>
					<v-card-title>Snapshots</v-card-title>
					<v-card-subtitle>Manually generated snapshots zips.</v-card-subtitle>
					<v-card-text>
						<span v-if="typeof dialog_site.environment_selected.details.snapshot_count == 'number'">{{ dialog_site.environment_selected.details.snapshot_count }} snapshots</v-show>
					</v-card-text>
				</v-card>
				</v-col>
				</v-row>
				</v-card>
				</v-sheet>
				<v-sheet v-show="dialog_site.backup_step == 2">
				<v-card flat>
					<v-subheader><a @click="dialog_site.backup_step = 1">Types</a>&nbsp;/ Backups</v-subheader>
					<v-card-text v-if="typeof dialog_site.environment_selected.backups == 'string'">
						<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
					</v-card-text>
					<div v-else>
					<v-data-table
						:headers="[{text:'Created At',value:'time'},{text:'Backup ID',value:'short_id',width:'115px'}]"
						:items="dialog_site.environment_selected.backups"
						item-key="id"
						no-data-text="No backups found."
                		:ref="'backup_table_'+ dialog_site.site.site_id + '_' + dialog_site.environment_selected.environment"
						single-expand
						show-expand
						class="table-backups"
						@click:row="expandBackup( $event, dialog_site.site.site_id, dialog_site.environment_selected.environment )"
					>
					<template v-slot:item.time="{ item }">
						{{ item.time | pretty_timestamp }}
					</template>
					<template v-slot:expanded-item="{ item }">
						<td colspan="3" style="position: relative;background: #fff; padding:0px">
						<v-row no-gutters justify="space-between">
						<v-col cols="5" md="5" sm="12">
						<v-progress-circular indeterminate color="primary" class="ma-5" size="24" v-show="item.loading"></v-progress-circular></span>
						<v-treeview
							v-model="item.tree"
							:items="item.files"
							activatable
							selectable
							selection-type="leaf"
							item-key="path"
							open-on-click
							return-object
						>
							<template v-slot:prepend="{ item, open }">
							<v-icon v-if="item.type == 'dir'">
								{{ open ? 'mdi-folder-open' : 'mdi-folder' }}
							</v-icon>
							<v-icon v-else>
								{{ files[item.ext] ? files[item.ext] : 'mdi-file' }}
							</v-icon>
							</template>
						</v-treeview>
						</v-col>
						<v-divider vertical></v-divider>
						<v-col class="pa-5 text-center">
						<v-alert type="info" dense text v-show="item.omitted">This backup has too many files to show. Uploaded files have been omitted for viewing purposes. Everything is still restorable.</v-alert>
						<v-scroll-y-transition mode="out-in">
						<div
							v-if="item.tree.length == 0"
							class="title font-weight-light"
							style="align-self: center;"
						>
							Select a file or folder.<br />
							<a class="body-2" @click="item.tree = item.files">Select everything</a>
						</div>
						<v-card
							v-else
							class="pt-6 mx-auto"
							flat
							max-width="400"
						>
							<v-card-text>
							<h3 class="headline mb-2">
								{{ item.tree.map( item => item.count ).reduce((a, b) => a + b, 0)  }} items selected
							</h3>
							<p>{{ item.tree.map( item => item.size ).reduce((a, b) => a + b, 0) | formatSize }}</p>
							</v-card-text>
							<v-divider></v-divider>
							<v-btn class="ma-2" @click="downloadBackup( item.id, item.tree )">Download<v-icon>mdi-file-download</v-icon></v-btn>
							<p class="mt-5 text-center"><a @click="item.tree = []">Cancel selection</a>
						</v-card>
						</v-scroll-y-transition>
					</v-col>
					</v-row>
						</td>
					</template>
					</v-data-table>
					</div>
				</v-card>
				</v-sheet>
				<v-sheet v-show="dialog_site.backup_step == 3">
				<v-card flat>
					<v-subheader><a @click="dialog_site.backup_step = 1 ">Types</a>&nbsp;/ Quicksaves</v-subheader>
					<v-card-text v-if="typeof dialog_site.environment_selected.quicksaves == 'string'">
						<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
					</v-card-text>
					<div v-else>
					<v-data-table
						:headers="[{text:'Created At',value:'created_at'},{text:'WordPress',value:'core',width:'115px'},{text:'',value:'themes',width:'100px'},{text:'',value:'plugins',width:'100px'}]"
						:items="dialog_site.environment_selected.quicksaves"
						item-key="quicksave_id"
						no-data-text="No quicksaves found."
                		:ref="'quicksave_table_'+ dialog_site.site.site_id + '_' + dialog_site.environment_selected.environment"
						@click:row="expandQuicksave( $event, dialog_site.site.site_id, dialog_site.environment_selected.environment )"
						single-expand
						show-expand
						class="table-quicksaves"
					>
					<template v-slot:item.created_at="{ item }">
						{{ item.created_at | pretty_timestamp_epoch }}
					</template>
					<template v-slot:item.core="{ item }">
						{{ item.core }}
					</template>
					<template v-slot:item.themes="{ item }">
						{{ item.themes.length }} themes
					</template>
					<template v-slot:item.plugins="{ item }">
						{{ item.plugins.length }} plugins
					</template>
					<template v-slot:expanded-item="{ item }">
						<td colspan="5" style="position: relative;background: #fff; padding:0px">
						<v-toolbar color="dark primary" dark dense light class="elevation-0">
							<v-toolbar-title class="body-2">{{ item.git_status }}</v-toolbar-title>
							<v-spacer></v-spacer>
							<v-toolbar-items>
                        <v-btn text small @click="QuicksavesRollback( dialog_site.site.site_id, item)">Rollback Everything</v-btn>
								<v-divider vertical class="mx-1" inset></v-divider>
                        <v-btn text small @click="viewQuicksavesChanges( dialog_site.site.site_id, item)">View Changes</v-btn>
							</v-toolbar-items>
						</v-toolbar>
						<v-card flat v-show="item.view_changes == true" style="table-layout:fixed;margin:0px;overflow: scroll;padding: 0px;position: absolute;background-color: #fff;width: 100%;left: 0;top: 100%;height: 100%;z-index: 3;transform: translateY(-100%);">
							<v-toolbar color="dark primary" dark dense light>
								<v-btn icon dark @click.native="item.view_changes = false">
									<v-icon>close</v-icon>
								</v-btn>
								<v-toolbar-title>List of changes</v-toolbar-title>
								<v-spacer></v-spacer>
							</v-toolbar>
								<v-card-text>
									<v-card-title>
										Files
									</v-card-title>
									<v-spacer></v-spacer>
									<v-layout>
										<v-flex sx12 sm9>
										</v-flex sx12 sm3>
										<v-flex>
										<v-text-field
											v-model="item.search"
											ref="quicksave_search"
											@input="filterFiles( dialog_site.site.site_id, item.quicksave_id)"
											append-icon="search"
											label="Search"
											single-line
											hide-details
										></v-text-field>
										</v-flex>
									</v-layout>
									<v-data-table 
										:headers='[{"text":"File","value":"file"}]'
										:items="item.filtered_files"
										:loading="item.loading"
										:footer-props="{ itemsPerPageOptions: [50,100,250,{'text':'All','value':-1}] }"
									>
										<template v-slot:body="{ items }">
										<tbody>
											<tr v-for="i in items">
												<td>
													<a class="v-menu__activator" @click="QuicksaveFileDiff(item.site_id, item.quicksave_id, item.git_commit, i)">{{ i }}</a>
												</td>
											</tr>
										</tbody>
										</template>
									</v-data-table>
								</v-card-text>
							</v-card>
						<v-card flat>
							<v-data-table
								:headers='[{"text":"Theme","value":"title"},{"text":"Version","value":"version"},{"text":"Status","value":"status"},{"text":"","value":"actions","width":"150px"}]'
								:items="item.themes"
								item-key="name"
								class="quicksave-table"
							>
							<template v-slot:body="{ items }">
							<tbody>
							<tr v-for="theme in items" v-bind:class="{ 'green lighten-5': theme.changed_version || theme.changed_status }">
								<td>{{ theme.title || theme.name }}</td>
								<td v-bind:class="{ 'green lighten-4': theme.changed_version }">{{ theme.version }}</td>
								<td v-bind:class="{ 'green lighten-4': theme.changed_status }">{{ theme.status }}</td>
								<td><v-btn depressed small @click="RollbackQuicksave(item.site_id, item.quicksave_id, 'theme', theme.name)">Rollback</v-btn></td>
							</tr>
							</template>
								<template v-slot:body.append="{ headers }">
								<tr class="red lighten-4 strikethrough" v-for="theme in quicksave.deleted_themes">
								<td>{{ theme.title || theme.name }}</td>
								<td>{{ theme.version }}</td>
								<td>{{ theme.status }}</td>
								<td></td>
								</tr>
								</tbody>
								</template>
							</v-data-table>
							<v-data-table
								:headers='[{"text":"Plugin","value":"plugin"},{"text":"Version","value":"version"},{"text":"Status","value":"status"},{"text":"","value":"actions","width":"150px"}]'
								:items="item.plugins"
								item-key="name"
								class="quicksave-table"
								:items-per-page="25"
								:footer-props="{ itemsPerPageOptions: [25,50,100,{'text':'All','value':-1}] }"
								>
								<template v-slot:body="{ items }">
								<tbody>
								<tr v-for="plugin in items" v-bind:class="[{ 'green lighten-5': plugin.changed_version || plugin.changed_status },{ 'red lighten-4 strikethrough': plugin.deleted }]">
								<td>{{ plugin.title || plugin.name }}</td>
								<td v-bind:class="{ 'green lighten-4': plugin.changed_version }">{{ plugin.version }}</td>
								<td v-bind:class="{ 'green lighten-4': plugin.changed_status }">{{ plugin.status }}</td>
								<td><v-btn depressed small @click="RollbackQuicksave(item.site_id, item.quicksave_id, 'plugin', plugin.name)" v-show="plugin.status != 'must-use' && plugin.status != 'dropin'">Rollback</v-btn></td>
								</tr>
								</template>
								<template v-slot:body.append="{ headers }">
								<tr class="red lighten-4 strikethrough" v-for="plugin in quicksave.deleted_plugins">
								<td>{{ plugin.title || plugin.name }}</td>
								<td>{{ plugin.version }}</td>
								<td>{{ plugin.status }}</td>
								<td></td>
								</tr>
								</tbody>
								</template>
							</v-data-table>
						</v-card>
						</td>
					</template>
					</v-data-table>
					</div>
					</v-card>
					</v-sheet>
					<v-sheet v-show="dialog_site.backup_step == 4">
					<v-card flat>
					<v-subheader><a @click="dialog_site.backup_step = 1">Types </a>&nbsp;/ Snapshots</v-subheader>
					<v-card-text v-if="typeof dialog_site.environment_selected.snapshots == 'string'">
						<span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span>
					</v-card-text>
					<div v-else>
					<v-data-table
						:headers="[{text:'Created At',value:'created_at',width:'250px'},{text:'User',value:'user',width:'125px'},{text:'Storage',value:'storage',width:'100px'},{text:'Notes',value:'notes'},{text:'',value:'actions',sortable: false,width:'190px'}]"
						:items="dialog_site.environment_selected.snapshots"
						item-key="snapshot_id"
						no-data-text="No snapshots found."
					>
					<template v-slot:item.user="{ item }">
						{{ item.user.name }}
					</template>
					<template v-slot:item.created_at="{ item }">
						{{ item.created_at | pretty_timestamp_epoch }}
					</template>
					<template v-slot:item.storage="{ item }">
						{{ item.storage | formatSize }}
					</template>
					<template v-slot:item.actions="{ item }">
					<template v-if="item.token && new Date() < new Date( item.expires_at )">
						<v-tooltip bottom>
							<template v-slot:activator="{ on }">
                    <v-btn small icon @click="fetchLink( dialog_site.site.site_id, item.snapshot_id )" v-on="on">
								<v-icon color="grey">mdi-sync</v-icon>
							</v-btn>
							</template>
							<span>Generate new link. Link valid for 24hrs.</span>
						</v-tooltip>
                <v-btn small rounded :href="`/wp-json/captaincore/v1/site/${dialog_site.site.site_id}/snapshots/${item.snapshot_id}-${item.token}/${item.snapshot_name.slice(0, -4)}`">Download</v-btn>
					</template>
					<template v-else>
						<v-tooltip bottom>
							<template v-slot:activator="{ on }">
                    <v-btn small icon @click="fetchLink( dialog_site.site.site_id, item.snapshot_id )" v-on="on">
								<v-icon color="grey">mdi-sync</v-icon>
							</v-btn>
							</template>
							<span>Generate new link. Link valid for 24hrs.</span>
						</v-tooltip>
						<v-btn small rounded disabled>Download</v-btn>
					</template>
					</template>
					</v-data-table>
					</div>
					</v-sheet>
			</v-tab-item>
		</v-tabs-items>
		<v-card flat v-else>
		<v-container fluid>
       		<div><span><v-progress-circular indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></span></div>
		 </v-container>
		</v-card>
	  </v-tab-item>
		<v-tab-item :key="8" value="tab-Timeline" :transition="false" :reverse-transition="false">
			<v-toolbar color="grey lighten-4" dense light flat>
				<v-toolbar-title>Timeline</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-toolbar-items>
					<v-btn text @click="exportTimeline()">Export <v-icon dark>mdi-file-download</v-icon></v-btn>
					<a ref="export_json" href="#"></a>
				</v-toolbar-items>
			</v-toolbar>
			<v-card flat>
			<v-data-table
				:headers="header_timeline"
        		:items="dialog_site.site.timeline"
				item-key="process_log_id"
				class="timeline"
				>
				<template v-slot:body="{ items }">
					<tbody>
					<tr v-for="item in items">
					<td class="justify-center">{{ item.created_at | pretty_timestamp_epoch }}</td>
					<td class="justify-center">{{ item.author }}</td>
					<td class="justify-center">{{ item.name }}</td>
					<td class="justify-center py-3" v-html="item.description"></td>
					<td>
						<v-btn text icon @click="editLogEntry(dialog_site.site.site_id, item.process_log_id)" v-if="role == 'administrator'">
							<v-icon small>edit</v-icon>
						</v-btn>
					</td>
				</tr>
				</tbody>
				</template>
			</v-data-table>
			</v-card>
		</v-tab-item>
	</v-tabs>
				</v-card>
			</v-sheet>
			<v-sheet v-show="dialog_site.step == 3">
				<v-toolbar flat color="grey lighten-4">
					<v-toolbar-title>Add Site</v-toolbar-title>
				<v-spacer></v-spacer>
					<v-btn icon @click="goToPath( `/account/sites` )">
						<v-icon>close</v-icon>
						</v-btn>
				</v-toolbar>
				<v-card-text>
					<v-form ref="form" :disabled="dialog_new_site.saving">
						<v-layout v-for="error in dialog_new_site.errors">
							<v-flex xs12>
								<v-alert :value="true" type="error">
								{{ error }}
								</v-alert>
							</v-flex>
						 </v-layout>
						<v-layout>
							<v-flex xs6 class="mx-2">
								<v-autocomplete
									:items='[{"name":"WP Engine","value":"wpengine"},{"name":"Kinsta","value":"kinsta"}]'
									item-text="name"
									v-model="dialog_new_site.provider"
									label="Provider"
								></v-autocomplete>
							</v-flex>
							<v-flex xs6 class="mx-2">
								<v-text-field :value="dialog_new_site.domain" @change.native="dialog_new_site.domain = $event.target.value" label="Domain name" required></v-text-field>
							</v-flex>
						</v-layout>
						<v-layout>
							<v-flex xs6 class="mx-2">
						    	<v-text-field :value="dialog_new_site.site" @change.native="dialog_new_site.site = $event.target.value" label="Site name" required hint="Should match provider site name." persistent-hint></v-text-field>
						</v-flex>
							<v-flex xs6 class="mx-2">
							<v-autocomplete
								:items="keys"
								v-model="dialog_new_site.key"
								item-text="title"
								item-value="key_id"
								label="SSH Key"
								hint="Optional. Will use SSH key instead of SFTP for management purposes."
								persistent-hint
							>
							</v-autocomplete>
						</v-flex>
						</v-layout>
						<v-layout>
							<v-flex xs12 class="mx-2">
							<v-autocomplete
								:items="accounts"
								v-model="dialog_new_site.shared_with"
								label="Assign to an account"
								item-text="name"
								item-value="account_id"
								chips
								deletable-chips
								multiple
								return-object
								hint="If a customer account is not assigned then a new account will be created automatically."
								persistent-hint
								:menu-props="{ closeOnContentClick:true, openOnClick: false }"
							>
							</v-autocomplete>
							<v-expand-transition>
							<v-row dense v-if="dialog_new_site.shared_with && dialog_new_site.shared_with.length > 0" class="mt-3">
							<v-col v-for="account in dialog_new_site.shared_with" :key="account.account_id" cols="4">
							<v-card>
								<v-card-title v-text="account.name"></v-card-title>
								<v-card-actions>
								<v-tooltip top>
								<template v-slot:activator="{ on, attrs }">
								<v-btn-toggle v-model="dialog_new_site.customer_id" color="primary" group>
									<v-btn text :value="account.account_id" v-bind="attrs" v-on="on">
										<v-icon>mdi-account-circle</v-icon>
									</v-btn>
								</v-btn-toggle>
								</template>
								<span>Set as customer contact</span>
								</v-tooltip>
								<v-tooltip top>
								<template v-slot:activator="{ on, attrs }">
								<v-btn-toggle v-model="dialog_new_site.account_id" color="primary" group>
									<v-btn text :value="account.account_id" v-bind="attrs" v-on="on">
										<v-icon>mdi-currency-usd</v-icon>
									</v-btn>
								</v-btn-toggle>
								</template>
								<span>Set as billing contact</span>
								</v-tooltip>
								</v-card-actions>
							</v-card>
							</v-expand-transition>
						</v-flex>
						</v-layout>
						<v-layout class="mt-5">
							<v-flex class="mx-2" xs6 v-for="(key, index) in dialog_new_site.environments" :key="key.index">
							<v-toolbar flat dense color="grey lighten-4">
								<div>{{ key.environment }} Environment</div>
								<v-spacer></v-spacer>
								<v-tooltip top v-if="key.environment == 'Staging'">
									<template v-slot:activator="{ on }">
										<v-btn text small icon color="red" @click="dialog_new_site.environments.splice( index )" v-on="on"><v-icon>delete</v-icon></v-btn>
									</template>
									<span>Delete Environment</span>
								</v-tooltip>
								<v-tooltip top v-if="key.environment == 'Staging'">
									<template v-slot:activator="{ on }">
										<v-btn text small icon color="green" @click="new_site_preload_staging()" v-on="on"><v-icon>cached</v-icon></v-btn>
									</template>
									<span>Preload based on Production</span>
								</v-tooltip>
							</v-toolbar>
							<v-text-field label="Address" :value="key.address" @change.native="key.address = $event.target.value" required hint="Should match included domain. Example: sitename.kinsta.cloud" persistent-hint></v-text-field>
							<v-text-field label="Home Directory" :value="key.home_directory" @change.native="key.home_directory = $event.target.value" required></v-text-field>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Username" :value="key.username" @change.native="key.username = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="ml-1"><v-text-field label="Password" :value="key.password" @change.native="key.password = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Protocol" :value="key.protocol" @change.native="key.protocol = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Port" :value="key.port" @change.native="key.port = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-switch label="Automatic Updates" v-model="key.updates_enabled" false-value="0" true-value="1"></v-switch></v-flex>
								<v-flex xs6 class="mr-1" v-if="typeof key.offload_enabled != 'undefined' && key.offload_enabled == 1">
									<v-switch label="Use Offload" v-model="key.offload_enabled" false-value="0" true-value="1" left></v-switch>
								</v-flex>
							</v-layout>
							<div v-if="key.offload_enabled == 1">
							<v-layout>
								<v-flex xs6 class="mr-1"><v-select label="Offload Provider" :value="key.offload_provider" @change.native="key.offload_provider = $event.target.value" :items='[{ provider:"s3", label: "Amazon S3" },{ provider:"do", label:"Digital Ocean" }]' item-text="label" item-value="provider" clearable></v-select></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Access Key" :value="key.offload_access_key" @change.native="key.offload_access_key = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Secret Key" :value="key.offload_secret_key" @change.native="key.offload_secret_key = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Bucket" :value="key.offload_bucket" @change.native="key.offload_bucket = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Path" :value="key.offload_path" @change.native="key.offload_path = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
						</div>
							</v-flex>
							<v-flex class="mx-2" xs6 v-show="dialog_new_site.environments.length == 1">
								<v-btn @click='dialog_new_site.environments.push( {"environment": "Staging", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" } )'>Add Staging Environment</v-btn>
							</v-flex>		
						</v-layout>
						<v-layout>
						 	<v-flex xs6><v-progress-circular v-show="dialog_new_site.saving" indeterminate color="primary" class="ma-2" size="24"></v-progress-circular></v-flex>
							<v-flex xs6 text-right>
								<v-dialog v-model="dialog_new_site.show_vars" scrollable hide-overlay max-width="400px">
								<template v-slot:activator="{ on }">
									<v-btn v-on="on" class="mr-2">Configure Environment Vars</v-btn>
								</template>
								<v-card>
										<v-list>
										<v-list-item>
											<v-list-item-content>
											<v-list-item-title>Environment Vars</v-list-item-title>
											<v-list-item-subtitle>Pass along with SSH requests</v-list-item-subtitle>
											</v-list-item-content>
											<v-list-item-action>
												<v-btn @click="addEnvironmentVarNewSite()">Add</v-btn>
											</v-list-item-action>
										</v-list-item>
										</v-list>
										<v-divider></v-divider>
										<v-list>
										<v-list-item v-for="(item, index) in dialog_new_site.environment_vars">
											<v-list-item-title>
												<v-row no-gutters>
													<v-col><v-text-field :value="item.key" @change.native="item.key = $event.target.value" label="Key"></v-text-field></v-col>
													<v-col><v-text-field :value="item.value" @change.native="item.value = $event.target.value" label="Value"></v-text-field></v-col>
												</v-row>
											</v-list-item-title>
											<v-list-item-action>
											<v-btn icon @click="removeEnvironmentVarNewSite(index)"><v-icon>mdi-delete</v-icon></v-btn>
											</v-list-item-action>
										</v-list-item>
										</v-list>
										<v-card-actions>
										<v-spacer></v-spacer>
										<v-btn color="primary" text @click="dialog_new_site.show_vars = false">Close</v-btn>
										</v-card-actions>
									</v-card>
								</v-dialog>
								<v-btn color="primary" right @click="submitNewSite()">Add Site</v-btn>
							</v-flex>
						</v-layout>
				</v-form>
	          </v-card-text>
			</v-sheet>
			<v-sheet v-show="dialog_site.step == 4">
				<v-toolbar flat color="grey lighten-4">
					<v-toolbar-title>Edit Site {{ dialog_edit_site.site.name }}</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-btn icon @click.native="dialog_site.step = 2">
						<v-icon>close</v-icon>
						</v-btn>
				</v-toolbar>
				<v-card-text>
				<v-form ref="form" :disabled="dialog_edit_site.loading">
					<v-layout v-for="error in dialog_edit_site.errors">
						<v-flex xs12>
							<v-alert
							:value="true"
							type="error"
							>
							{{ error }}
							</v-alert>
						</v-flex>
					</v-layout>
					<v-layout>
						<v-flex xs6 class="mx-2">
						<v-autocomplete
							:items='[{"name":"WP Engine","value":"wpengine"},{"name":"Kinsta","value":"kinsta"}]'
							item-text="name"
							v-model="dialog_edit_site.site.provider"
							label="Provider"
						></v-autocomplete>
						</v-flex>
						<v-flex xs6 class="mx-2">
							<v-text-field :value="dialog_edit_site.site.name" @change.native="dialog_edit_site.site.name = $event.target.value" label="Domain name" required></v-text-field>
						</v-flex>
					</v-layout>
					<v-layout>
						<v-flex xs6 class="mx-2">
							<v-text-field :value="dialog_edit_site.site.site" @change.native="dialog_edit_site.site.site = $event.target.value" label="Site name (not changeable)" disabled></v-text-field>
						</v-flex>
						<v-flex xs6 class="mx-2">
							<v-autocomplete
								:items="keys"
								item-text="title"
								item-value="key_id"
								v-model="dialog_edit_site.site.key"
								label="SSH Key"
								hint="Optional. Will use SSH key instead of SFTP for management purposes."
								persistent-hint
							>
							</v-autocomplete>
						</v-flex>
					</v-layout>
					<v-layout>
						<v-flex xs12 class="mx-2">
							<v-autocomplete
								:items="accounts"
								v-model="dialog_edit_site.site.shared_with"
								label="Assign to an account"
								item-text="name"
								item-value="account_id"
								chips
								deletable-chips
								multiple
								return-object
								hint="If a customer account is not assigned then a new account will be created automatically."
								persistent-hint
								:menu-props="{ closeOnContentClick:true, openOnClick: false }"
							>
							</v-autocomplete>
							<v-expand-transition>
							<v-row dense v-if="dialog_edit_site.site.shared_with && dialog_edit_site.site.shared_with.length > 0" class="mt-3">
							<v-col v-for="account in dialog_edit_site.site.shared_with" :key="account.account_id" cols="4">
							<v-card>
								<v-card-title v-html="account.name"></v-card-title>
								<v-card-actions>
								<v-tooltip top>
								<template v-slot:activator="{ on, attrs }">
								<v-btn-toggle v-model="dialog_edit_site.site.customer_id" color="primary" group>
									<v-btn text :value="account.account_id" v-bind="attrs" v-on="on">
										<v-icon>mdi-account-circle</v-icon>
									</v-btn>
								</v-btn-toggle>
								</template>
								<span>Set as customer contact</span>
								</v-tooltip>
								<v-tooltip top>
								<template v-slot:activator="{ on, attrs }">
								<v-btn-toggle v-model="dialog_edit_site.site.account_id" color="primary" group>
									<v-btn text :value="account.account_id" v-bind="attrs" v-on="on">
										<v-icon>mdi-currency-usd</v-icon>
									</v-btn>
								</v-btn-toggle>
								</template>
								<span>Set as billing contact</span>
								</v-tooltip>
								</v-card-actions>
							</v-card>
							</v-expand-transition>
						</v-flex>
					</v-layout>
					<v-layout class="mt-5">
						<v-flex class="mx-2" xs6 v-for="(key, index) in dialog_edit_site.site.environments" :key="key.index">
							<v-toolbar flat dense color="grey lighten-4">
								<div>{{ key.environment }} Environment</div>
								<v-spacer></v-spacer>
								<v-tooltip top v-if="key.environment == 'Staging'">
									<template v-slot:activator="{ on }">
										<v-btn text small icon color="red" @click="dialog_edit_site.site.environments.splice( index )" v-on="on"><v-icon>delete</v-icon></v-btn>
									</template>
									<span>Delete Environment</span>
								</v-tooltip>
								<v-tooltip top v-if="key.environment == 'Staging'">
									<template v-slot:activator="{ on }">
										<v-btn text small icon color="green" @click="edit_site_preload_staging()" v-on="on"><v-icon>cached</v-icon></v-btn>
									</template>
									<span>Preload based on Production</span>
								</v-tooltip>
							</v-toolbar>
							<v-text-field label="Address" :value="key.address" @change.native="key.address = $event.target.value" required></v-text-field>
							<v-text-field label="Home Directory" :value="key.home_directory" @change.native="key.home_directory = $event.target.value" required></v-text-field>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Username" :value="key.username" @change.native="key.username = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="ml-1"><v-text-field label="Password" :value="key.password" @change.native="key.password = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Protocol" :value="key.protocol" @change.native="key.protocol = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Port" :value="key.port" @change.native="key.port = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
									<v-flex xs6 class="mr-1" v-if="typeof key.offload_enabled != 'undefined' && key.offload_enabled == 1">
										<v-switch label="Use Offload" v-model="key.offload_enabled" false-value="0" true-value="1" left></v-switch>
									</v-flex>
							</v-layout>
							<div v-if="key.offload_enabled == 1">
							<v-layout>
								<v-flex xs6 class="mr-1"><v-select label="Offload Provider" :value="key.offload_provider" @change.native="key.offload_provider = $event.target.value" :items='[{ provider:"s3", label: "Amazon S3" },{ provider:"do", label:"Digital Ocean" }]' item-text="label" item-value="provider" clearable></v-select></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Access Key" :value="key.offload_access_key" @change.native="key.offload_access_key = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Secret Key" :value="key.offload_secret_key" @change.native="key.offload_secret_key = $event.target.value" required></v-text-field></v-flex>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Bucket" :value="key.offload_bucket" @change.native="key.offload_bucket = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
							<v-layout>
								<v-flex xs6 class="mr-1"><v-text-field label="Offload Path" :value="key.offload_path" @change.native="key.offload_path = $event.target.value" required></v-text-field></v-flex>
							</v-layout>
					</div>
						</v-flex>
						<v-flex class="mx-2" xs6 v-show="dialog_edit_site.site.environments.length == 1">
							<v-btn @click='dialog_edit_site.site.environments.push( {"environment": "Staging", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" } )'>Add Staging Environment</v-btn>
						</v-flex>	
					</v-layout>
					<v-layout>
						<v-flex xs6><v-progress-circular v-show="dialog_edit_site.loading" indeterminate color="primary"></v-progress-linear></v-flex>
						<v-flex xs6 text-right>
						<v-dialog v-model="dialog_edit_site.show_vars" scrollable hide-overlay max-width="400px">
						<template v-slot:activator="{ on }">
							<v-btn v-on="on" class="mr-2">Configure Environment Vars</v-btn>
						</template>
						<v-card>
								<v-list>
								<v-list-item>
									<v-list-item-content>
									<v-list-item-title>Environment Vars</v-list-item-title>
									<v-list-item-subtitle>Pass along with SSH requests</v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-action>
										<v-btn @click="addEnvironmentVar()">Add</v-btn>
									</v-list-item-action>
								</v-list-item>
								</v-list>
								<v-divider></v-divider>
								<v-list>
								<v-list-item v-for="(item, index) in dialog_edit_site.site.environment_vars">
									<v-list-item-title>
										<v-row no-gutters>
											<v-col><v-text-field :value="item.key" @change.native="item.key = $event.target.value" label="Key"></v-text-field></v-col>
											<v-col><v-text-field :value="item.value" @change.native="item.value = $event.target.value" label="Value"></v-text-field></v-col>
										</v-row>
									</v-list-item-title>
									<v-list-item-action>
									<v-btn icon @click="removeEnvironmentVar(index)"><v-icon>mdi-delete</v-icon></v-btn>
									</v-list-item-action>
								</v-list-item>
								</v-list>
								<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn color="primary" text @click="dialog_edit_site.show_vars = false">Close</v-btn>
								</v-card-actions>
				</v-card>
						</v-dialog>
							<v-btn right @click="updateSite" color="primary">
								Save Changes
							</v-btn>
						</v-flex>
					 </v-layout>
				</v-form>
				</v-card-text>
			</v-sheet>
			</v-card>
			<v-card tile v-if="route == 'dns'" flat>
			<v-sheet v-show="dialog_domain.step == 1">
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ allDomains }} domains</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn text @click="dialog_new_domain.show = true" v-show="role == 'administrator'">Add Domain <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text>
				<v-card class="mb-4 dns_introduction">
					<v-alert
						:value="true"
						type="info"
						style="padding:8px 16px;"
					>
					<v-layout wrap align-center justify-center row fill-height>
					<v-flex xs12 md9 px-2 subtitle-1>
						<div v-html="configurations.dns_introduction_html"></div>
					</v-flex>
					<v-flex xs12 md3 px-2 text-center v-show="configurations.dns_nameservers != ''">
						<v-chip color="primary">Nameservers</v-chip>
						<div v-html="configurations.dns_nameservers"></div>
					</v-flex>
					</v-layout>
					</v-alert>
				</v-card>
				<v-row class="ma-0 pa-0">
					<v-col class="ma-0 pa-0"></v-col>
					<v-col class="ma-0 pa-0"sm="12" md="4">
					<v-text-field
						v-model="domain_search"
						append-icon="search"
						label="Search"
						single-line
						clearable
						autofocus
						hide-details
					></v-text-field>
					</v-col>
				</v-row>
				</v-card-text>
				<v-data-table
					:headers="[{ text: 'Name', value: 'name' }]"
					:items="domains"
					:search="domain_search"
					:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
				>
				<template v-slot:body="{ items }">
					<tbody>
					<tr v-for="item in items" @click="goToPath( `/account/dns/${item.domain_id}`)" style="cursor:pointer;">
						<td>{{ item.name }}</td>
					</tr>
					</tbody>
				</template>
				</v-data-table>
				</v-sheet>
				<v-sheet v-show="dialog_domain.step == 2">
					<v-card tile flat>
						<v-toolbar flat color="grey lighten-4">
							<v-toolbar-title>
							<v-autocomplete
								v-model="dialog_domain.domain"
								:items="domains"
								return-object
								item-text="name"
								@input="goToPath( `/account/dns/${dialog_domain.domain.domain_id}`)"
								class="mt-5"
								spellcheck="false"
								flat
							></v-autocomplete>
							</v-toolbar-title>
							<span v-show="dnsRecords > 0" class="body-2 ml-4">{{ dnsRecords }} records</span>
							<v-spacer></v-spacer>
							<v-toolbar-items>
								<v-btn text href="/account/dns" @click.prevent="goToPath( '/account/dns' )"><v-icon>mdi-arrow-left</v-icon> Back</v-btn>
							</v-toolbar-items>
						</v-toolbar>
						<v-row v-if="dialog_domain.errors">
							<v-col class="mx-3">
								<v-alert :value="true" type="error" v-for="error in dialog_domain.errors">{{ error }}</v-alert>
							</v-col>
						</v-row>
						<v-row>
							<v-col>
								<v-progress-circular indeterminate color="primary" size="24" class="ml-4" v-show="dialog_domain.loading"></v-progress-circular>
								<div class="v-data-table theme--light">
								<div class="v-data-table__wrapper">
								<table class="table-dns" v-show="dialog_domain.records.length > 0">
									<thead class="v-data-table-header">
									<tr>
										<th width="175">Type</th>
										<th width="200">Name</th>
										<th>Value</th>
										<th width="75">TTL</th>
										<th width="95"></th>
									</tr>
									</thead>
									<tbody>
									<tr v-for="(record, index) in dialog_domain.records" :key="record.id" v-bind:class="{ new: record.new, edit: record.edit, delete: record.delete }">
									<template v-if="record.edit">
										<td class="pt-3">{{ record.type }}</td>
										<td><v-text-field label="Name" :value="record.update.record_name" @change.native="record.update.record_name = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></td>
										<td class="value" v-if="record.type == 'MX'">
											<v-layout v-for="(value, value_index) in record.update.record_value">
												<v-flex xs3><v-text-field label="Level" :value="value.level" @change.native="value.level = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs9><v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'><template v-slot:append-outer><v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecordValue( index, value_index )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn></template></v-text-field></v-flex>
											</v-layout>
											<v-btn icon small color="primary" class="ma-0 mb-3" @click="addRecordValue( index )" v-show="!dialog_domain.loading && !dialog_domain.saving"><v-icon>mdi-plus-box</v-icon></v-btn>
										</td>
										<td class="value" v-else-if="record.type == 'A' || record.type == 'AAAA' || record.type == 'ANAME' || record.type == 'TXT' || record.type == 'SPF'">
											<div v-for="(value, value_index) in record.update.record_value" :key="`value-${index}-${value_index}`">
												<v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'><template v-slot:append-outer><v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecordValue( index, value_index )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn></template></v-text-field>
											</div>
											<v-btn icon small color="primary" class="ma-0 mb-3" @click="addRecordValue( index )" v-show="!dialog_domain.loading && !dialog_domain.saving"><v-icon>mdi-plus-box</v-icon></v-btn>
										</td>
										<td class="value" v-else-if="record.type == 'SRV'">
											<v-layout v-for="value in record.update.record_value">
												<v-flex xs2><v-text-field label="Priority" :value="value.priority" @change.native="value.priority = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs2><v-text-field label="Weight" :value="value.weight" @change.native="value.weight = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs2><v-text-field label="Port" :value="value.port" @change.native="value.port = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs6><v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
											</v-layout>
											<v-btn icon small color="primary" class="ma-0 mb-3" @click="addRecordValue( index )" v-show="!dialog_domain.loading && !dialog_domain.saving"><v-icon>mdi-plus-box</v-icon></v-btn>
										</td>
										<td class="value" v-else>
											<v-text-field label="Value" :value="record.update.record_value" @change.native="record.update.record_value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field>
										</td>
										<td><v-text-field label="TTL" :value="record.update.record_ttl" @change.native="record.update.record_ttl = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></td>
										<td class="text-right pt-3">
											<v-btn text small icon color="primary" class="ma-0 pa-0" @click="viewRecord( record.id )" :disabled="dialog_domain.saving"><v-icon>mdi-pencil-box</v-icon></v-btn>
											<v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecord( record.id )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn>
										</td>
									</template>
									<template v-else-if="record.new">
										<td><v-select v-model="record.type" @input="changeRecordType( index )" item-text="name" item-value="value" :items='[{"name":"A","value":"A"},{"name":"AAAA","value":"AAAA"},{"name":"ANAME","value":"ANAME"},{"name":"CNAME","value":"CNAME"},{"name":"HTTP Redirect","value":"HTTPRedirection"},{"name":"MX","value":"MX"},{"name":"SRV","value":"SRV"},{"name":"TXT","value":"TXT"}]' label="Type" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-select></td>
										<td><v-text-field label="Name" :value="record.update.record_name" @change.native="record.update.record_name = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></td>
										<td class="value" v-if="record.type == 'MX'">
											<v-layout v-for="(value, value_index) in record.update.record_value">
												<v-flex xs3><v-text-field label="Level" :value="value.level" @change.native="value.level = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs9><v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'><template v-slot:append-outer><v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecordValue( index, value_index )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn></template></v-text-field></v-flex>
											</v-layout>
											<v-btn icon small color="primary" class="ma-0 mb-3" @click="addRecordValue( index )" v-show="!dialog_domain.loading && !dialog_domain.saving"><v-icon>mdi-plus-box</v-icon></v-btn>
										</td>
										<td class="value" v-else-if="record.type == 'A' || record.type == 'AAAA' || record.type == 'ANAME' || record.type == 'TXT' || record.type == 'SPF'">
											<div v-for="(value, value_index) in record.update.record_value" :key="`value-${index}-${value_index}`">
												<v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'><template v-slot:append-outer><v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecordValue( index, value_index )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn></template></v-text-field>
											</div>
											<v-btn icon small color="primary" class="ma-0 mb-3" @click="addRecordValue( index )" v-show="!dialog_domain.loading && !dialog_domain.saving"><v-icon>mdi-plus-box</v-icon></v-btn>
										</td>
										<td class="value" v-else-if="record.type == 'SRV'">
											<v-layout v-for="value in record.update.record_value">
												<v-flex xs2><v-text-field label="Priority" :value="value.priority" @change.native="value.priority = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs2><v-text-field label="Weight" :value="value.weight" @change.native="value.weight = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs2><v-text-field label="Port" :value="value.port" @change.native="value.port = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
												<v-flex xs6><v-text-field label="Value" :value="value.value" @change.native="value.value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></v-flex>
											</v-layout>
										</td>
										<td class="value" v-else>
											<v-text-field label="Value" :value="record.update.record_value" @change.native="record.update.record_value = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field>
										</td>
										<td><v-text-field label="TTL" :value="record.update.record_ttl" @change.native="record.update.record_ttl = $event.target.value" v-bind:class='{ "v-input--is-disabled": dialog_domain.saving }'></v-text-field></td>
										<td class="text-right pt-3">
											<v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteRecord( index )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn>
										</td>
									</template>
									<template v-else>
										<td>{{ record.type }}</td>
										<td class="name">{{ record.name }}</td>
										<td class="value" v-if="record.type == 'MX'"><div v-for="value in record.value">{{ value.level }} {{ value.value }}</div></td>
										<td class="value" v-else-if="record.type == 'A' || record.type == 'AAAA' || record.type == 'ANAME' || record.type == 'TXT' || record.type == 'SPF'"><div v-for="value in record.value">{{ value.value }}</div></td>
										<td class="value" v-else-if="record.type == 'SRV'"><div v-for="value in record.value">{{ value.priority }} {{ value.weight }} {{ value.port }} {{ value.value }}</div></td>
										<td class="value" v-else>{{ record.value }}</td>
										<td>{{ record.ttl }}</td>
										<td class="text-right">
											<v-btn text small icon color="primary" class="ma-0 pa-0" @click="editRecord( record.id )" :disabled="dialog_domain.saving"><v-icon>mdi-pencil-box</v-icon></v-btn>
											<v-btn text small icon color="primary" class="ma-0 pa-0" @click="deleteCurrentRecord( record.id )" :disabled="dialog_domain.saving"><v-icon>mdi-delete</v-icon></v-btn>
										</td>
									</template>
									</tr>
								</tbody>
								</table>
								</div>
								</div>
								<v-btn depressed class="ml-2" @click="addRecord()" v-show="!dialog_domain.loading && !dialog_domain.saving && !dialog_domain.errors">Add Additional Record</v-btn>
							</v-col>
						</v-row>
    					<v-row>
							<v-col cols="12" class="px-5" v-show="dialog_domain.show_import == true">
								<v-textarea 
									placeholder="Paste JSON export here." 
									outlined
									persistent-hint 
									hint="Paste JSON export then click Load JSON. Warning, all existing records will be overwritten." 
									:value="dialog_domain.import_json" 
									@change.native="dialog_domain.import_json = $event.target.value" 
									spellcheck="false">
								</v-textarea>
								<v-btn depressed class="ma-0" @click="importDomain()">Load JSON</v-btn>
							</v-col>
						</v-row>
    					<v-row>
							<v-col>
								<v-progress-circular indeterminate color="primary" size="24" class="ml-4" v-show="dialog_domain.saving"></v-progress-circular>
							</v-col>
						</v-row>
    					<v-row>
							<v-col class="text-right mx-3" v-show="!dialog_domain.loading">
								<v-btn class="mx-1" depressed @click="deleteDomain()" v-if="role == 'administrator'">Delete Domain</v-btn>
								<v-btn class="mx-1" depressed @click="dialog_domain.show_import = true" class="mx-3">Import <v-icon dark>mdi-file-upload</v-icon></v-btn>
								<v-btn class="mx-1" depressed @click="exportDomain()">Export <v-icon dark>mdi-file-download</v-icon></v-btn>
								<v-btn class="mx-1" depressed color="primary" @click="saveDNS()" :dark="dialog_domain.records && dialog_domain.records.length != '0'" :disabled="dialog_domain.records && dialog_domain.records.length == '0'">Save Records</v-btn>
								<a ref="export_domain" href="#"></a>
							</v-col>
						</v-row>
    					<v-row>
							<v-col class="mx-3">
								<template v-for="result in dialog_domain.results">
									<v-alert :value="true" type="success" v-show="typeof result.success != 'undefined'">{{ result.success }}</v-alert>
									<v-alert :value="true" type="error" v-show="typeof result.errors != 'undefined'">{{ result.errors }}</v-alert>
								</template>
							</v-col>
						</v-row>
					</v-card>
				</v-sheet>
			</v-card>
			<v-card tile v-if="route == 'health'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ filterSitesWithErrors.length }} sites with issues</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text>
				<v-alert :value="true" type="info">
						Results from daily scans of home pages. Web console errors are extracted from Google Chrome via Lighthouse CLI. Helpful for tracking down wide range of issues.  
				</v-alert>
					<v-card v-for="site in filterSitesWithErrors" flat class="mb-2" :key="site.site_id">
					<v-toolbar color="grey lighten-4" light flat>
						<v-img :src=`${remote_upload_uri}${site.site}_${site.site_id}/production/screenshots/${site.screenshot_base}_thumb-100.jpg` class="elevation-1 mr-3" max-width="50" v-show="site.screenshot_base"></v-img>
						<v-toolbar-title>{{ site.name }}</v-toolbar-title>
						<v-spacer></v-spacer>
						<v-toolbar-items>
							<v-btn small text @click="scanErrors( site )">
								Scan <v-icon class="ml-1">mdi-sync</v-icon>
							</v-btn>
							<v-btn small text :href="`http://${site.name}`" target="_blank">
								View <v-icon class="ml-1">mdi-open-in-new</v-icon> 
							</v-btn>
							<v-btn small text @click="copySSH( site )">
								SSH <v-icon class="ml-1">mdi-content-copy</v-icon> 
							</v-btn>
							<v-btn small text @click="showLogEntry( site.site_id )" v-show="role == 'administrator'">
								Log <v-icon class="ml-1">mdi-check</v-icon>
							</v-btn>
							<v-chip class="mt-4 ml-2" label :input-value="true">{{ site.console_errors.length }} issues</v-chip>
						</v-toolbar-items>
					</v-toolbar>
					<v-card class="elevation-0 mx-auto" v-for="error in site.console_errors">
						<v-card-title>{{ error.source }}</v-card-title>
						<v-card-subtitle><a :href="error.url">{{ error.url }}</a></small></v-card-subtitle>
						<v-card-text>
							<pre><code>{{ error.description }}</code></pre>
						</v-card-text>
					</v-card>
					<v-overlay absolute :value="site.loading">
						<v-progress-circular indeterminate size="64" width="4"></v-progress-circular>
					</v-overlay>
					</v-card>
				</v-card-text>
			</v-card>
			<v-card tile v-if="route == 'cookbook'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ filteredRecipes.length }} recipes</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn text @click="new_recipe.show = true">Add recipe <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text>
				<v-alert
					:value="true"
					type="info"
				>
						Warning, this is for developers only 💻. The cookbook contains user made "recipes" or scripts which are deployable to one or many sites. Bash script and WP-CLI commands welcomed. For ideas refer to <code><a href="https://captaincore.io/cookbook/" target="_blank">captaincore.io/cookbook</a></code>.
				</v-alert>
				</v-card-text>
				<v-data-table
					:headers="[{ text: 'Title', value: 'title' }]"
					:items="filteredRecipes"
					:sort-by="['title']"
					:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
					v-show="filteredRecipes.length != 0"
				>
				<template v-slot:body="{ items }">
					<tbody>
					<tr v-for="item in items" :key="item.recipe_id" @click="editRecipe( item.recipe_id )" style="cursor:pointer;">
						<td>{{ item.title }}</td>
					</tr>
					</tbody>
				</template>
				</v-data-table>
			</v-card>
			<v-card tile v-if="route == 'handbook' && role == 'administrator'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ processes.length }} processes</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn text small @click="fetchProcessLogs()" v-on="on"><v-icon dark>mdi-timeline-text-outline</v-icon></v-btn>
							</template>
							<span>Log History</span>
						</v-tooltip>
						<v-divider vertical class="mx-1" inset></v-divider>
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn text small @click="showLogEntryGeneric()" v-on="on"><v-icon dark>mdi-check</v-icon></v-btn>
							</template>
							<span>Add Log Entry</span>
						</v-tooltip>
						<v-divider vertical class="mx-1" inset></v-divider>
						<v-btn text @click="new_process.show = true">Add process <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text style="max-height: 100%;">
					<v-container fluid grid-list-lg>
					<v-layout row wrap>
					<v-flex xs12 v-for="process in processes">
						<v-card :hover="true" @click="viewProcess( process.process_id )">
						<v-card-title primary-title class="pt-2">
							<div>
								<span class="title">{{ process.name }}</a> <v-chip color="primary" text-color="white" text v-show="process.roles != ''">{{ process.roles }}</v-chip></span>
								<div class="caption">
									<v-icon v-show="process.time_estimate != ''" style="padding:0px 5px">mdi-clock-outline</v-icon>{{ process.time_estimate }} 
									<v-icon v-show="process.repeat_interval != '' && process.repeat_interval != null" style="padding:0px 5px">mdi-calendar-repeat</v-icon>{{ process.repeat_interval }} 
									<v-icon v-show="process.repeat_quantity != '' && process.repeat_quantity != null" style="padding:0px 5px">mdi-repeat</v-icon>{{ process.repeat_quantity }}
								</div>
							</div>
						</v-card-title>
						</v-card>
					</v-flex>
					</v-layout>
					</v-container>
				</v-card-text>
			</v-card>
			<v-card tile v-if="route == 'configurations' && role == 'administrator'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Configurations</v-toolbar-title>
					<v-spacer></v-spacer>
				</v-toolbar>
				<v-card-text>
				<span class="body-2">Theme colors</span>
				<v-row>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Primary" v-model="$vuetify.theme.themes.light.primary" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.primary" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.primary, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.primary" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Secondary" v-model="$vuetify.theme.themes.light.secondary" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.secondary" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.secondary, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.secondary" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Accent" v-model="$vuetify.theme.themes.light.accent" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.accent" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.accent, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.accent" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Error" v-model="$vuetify.theme.themes.light.error" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.error" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.error, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.error" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Info" v-model="$vuetify.theme.themes.light.info" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.info" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.info, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.info" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Success" v-model="$vuetify.theme.themes.light.success" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.success" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.success, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.success" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col class="shrink" style="min-width: 220px;">
						<v-text-field persistent-hint hint="Warning" v-model="$vuetify.theme.themes.light.warning" class="ma-0 pa-0" solo>
						<template v-slot:append>
							<v-menu v-model="colors.warning" top nudge-bottom="126" nudge-left="14" :close-on-content-click="false">
								<template v-slot:activator="{ on }">
									<div :style="{ backgroundColor: $vuetify.theme.themes.light.warning, cursor: 'pointer', height: '30px', width: '30px', borderRadius: '4px', transition: 'border-radius 200ms ease-in-out' }" v-on="on"></div>
								</template>
								<v-card>
									<v-card-text class="pa-0">
										<v-color-picker v-model="$vuetify.theme.themes.light.warning" flat></v-color-picker>
									</v-card-text>
								</v-card>
							</v-menu>
						</template>
						</v-text-field>
					</v-col>
					<v-col><v-btn @click="resetColors">Reset colors</a></v-btn>
				</v-row>
				<v-row>
					<v-col :md="5">
						<v-text-field v-model="configurations.name" label="Name"></v-text-field>
					</v-col>
					<v-col :md="5">
						<v-text-field v-model="configurations.logo" label="Logo URL"></v-text-field>
					</v-col>
					<v-col :md="2">
						<v-text-field v-model="configurations.logo_width" label="Logo Width"></v-text-field>
					</v-col>
				</v-row>
				<v-row>
					<v-col>
						<v-textarea v-model="configurations.dns_introduction" label="DNS Introduction"></v-textarea>
					</v-col>
				</v-row>
				<v-row>
					<v-col>
						<v-textarea v-model="configurations.dns_nameservers" label="DNS Nameservers"></v-textarea>
					</v-col>
				</v-row>
				<span class="body-2">Hosting Plans</span>
				<v-row v-for="(plan, index) in configurations.hosting_plans">
					<v-col>
						<v-text-field v-model="plan.name" label="Name"></v-text-field>
					</v-col>
					<v-col style="max-width:100px">
						<v-text-field v-model="plan.interval" label="Interval" hint="# of months" persistent-hint></v-text-field>
					</v-col>
					<v-col style="max-width:100px">
						<v-text-field v-model="plan.price" label="Price"></v-text-field>
					</v-col>
					<v-col style="max-width:150px">
						<v-text-field v-model="plan.limits.visits" label="Visits Limits"></v-text-field>
					</v-col>
					<v-col style="max-width:150px">
						<v-text-field v-model="plan.limits.storage" label="Storage Limits"></v-text-field>
					</v-col>
					<v-col style="max-width:120px">
						<v-text-field v-model="plan.limits.sites" label="Sites Limits"></v-text-field>
					</v-col>
					<v-col class="ma-0 pa-0" style="max-width:46px">
						<v-btn color="red" icon @click="deletePlan( index )"><v-icon>mdi-delete</v-icon></v-btn>
					</v-col>
				</v-row>
				<v-row>
					<v-col><v-btn @click="addAdditionalPlan()">Add Additional Plan</v-btn></v-col>
				</v-row>
				<div class="seperator mt-5"></div>
				<span class="body-2">Usage Pricing</span>
				<v-row>
					<v-col style="max-width:200px"><v-text-field label="Sites Quantity" v-model="configurations.usage_pricing.sites.quantity"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Sites Cost" v-model="configurations.usage_pricing.sites.cost"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Sites Interval" v-model="configurations.usage_pricing.sites.interval" hint="# of months" persistent-hint></v-text-fiel></v-col>
				</v-row>
				<v-row>
					<v-col style="max-width:200px"><v-text-field label="Storage Quantity (GB)" v-model="configurations.usage_pricing.storage.quantity"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Storage Cost" v-model="configurations.usage_pricing.storage.cost"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Storage Interval" v-model="configurations.usage_pricing.storage.interval" hint="# of months" persistent-hint></v-text-fiel></v-col>
				</v-row>
				<v-row>
					<v-col style="max-width:200px"><v-text-field label="Traffic Quantity (pageviews)" v-model="configurations.usage_pricing.traffic.quantity"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Traffic Cost" v-model="configurations.usage_pricing.traffic.cost"></v-text-field></v-col>
					<v-col style="max-width:150px"><v-text-field label="Traffic Interval" v-model="configurations.usage_pricing.traffic.interval" hint="# of months" persistent-hint></v-text-fiel></v-col>
				</v-row>
				<v-flex xs12 text-right>
					<v-btn color="primary" dark @click="saveGlobalConfigurations()">
						Save Configurations
					</v-btn>
				</v-flex>

				</v-card-text>
			</v-card>
			<v-card tile v-show="route == 'defaults'" v-if="role == 'administrator'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Site Defaults</v-toolbar-title>
					<v-spacer></v-spacer>
				</v-toolbar>
				<v-card-text>
					<v-alert :value="true" type="info" class="mb-4 mt-4">
						When new sites are added then the following default settings will be applied.  
					</v-alert>
					<v-layout wrap>
						<v-flex xs6 pr-2><v-text-field :value="defaults.email" @change.native="defaults.email = $event.target.value" label="Default Email" required></v-text-field></v-flex>
						<v-flex xs6 pl-2><v-autocomplete :items="timezones" label="Default Timezone" v-model="defaults.timezone"></v-autocomplete></v-flex>
					</v-layout>
					<v-layout wrap>
						<v-flex><v-autocomplete label="Default Recipes" v-model="defaults.recipes" ref="default_recipes" :items="recipes" item-text="title" item-value="recipe_id" multiple chips deletable-chips></v-autocomplete></v-flex>
					</v-layout>

					<span class="body-2">Default Users</span>
					<v-data-table
						:items="defaults.users"
						hide-default-header
						hide-default-footer
						v-if="typeof defaults.users == 'object'"
					>
					<template v-slot:body="{ items }">
					<tbody>
						<tr v-for="(item, index) in items" style="border-bottom: 0px;">
							<td class="pa-1"><v-text-field :value="item.username" @change.native="item.username = $event.target.value" label="Username"></v-text-field></td>
							<td class="pa-1"><v-text-field :value="item.email" @change.native="item.email = $event.target.value" label="Email"></v-text-field></td>
							<td class="pa-1"><v-text-field :value="item.first_name" @change.native="item.first_name = $event.target.value" label="First Name"></v-text-field></td>
							<td class="pa-1"><v-text-field :value="item.last_name" @change.native="item.last_name = $event.target.value" label="Last Name"></v-text-field></td>
							<td class="pa-1" style="width:145px;"><v-select :value="item.role" v-model="item.role" :items="roles" label="Role" item-text="name"></v-select></td>
							<td class="pa-1"><v-btn text small icon color="primary" @click="deleteGlobalUserValue( index )"><v-icon small>mdi-delete</v-icon></v-btn></td>
						</tr>
					</tbody>
					</template>
						<template v-slot:footer>
						<tr style="border-top: 0px;">
							<td colspan="5" style="padding:0px;">
								<v-btn depressed small class="ma-0 mb-3" @click="addGlobalDefaultsUser()">Add Additional User</v-btn>
							</td>
						</tr>
						</template>
					</v-data-table>

					<v-flex xs12 text-right>
						<v-btn color="primary" dark @click="saveGlobalDefaults()">
							Save Changes
						</v-btn>
					</v-flex>
				</v-card-text>
			</v-card>
			<v-card tile v-if="route == 'keys' && role == 'administrator'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Your SSH keys</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn text @click="new_key.show = true">Add SSH Key <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text style="max-height: 100%;">
					<v-container fluid grid-list-lg>
					<v-layout row wrap>
					<v-flex xs12 v-for="key in keys" :key="key.key_id">
						<v-card :hover="true" @click="viewKey( key.key_id )">
						<v-card-title primary-title class="pt-2">
							<div>
								<span class="title">{{ key.title }}</a></span>
							</div>
						</v-card-title>
						<v-card-text>
							<v-chip color="primary" text-color="white" text>{{ key.fingerprint }}</v-chip>
						</v-card-text>
						</v-card>
					</v-flex>
					</v-layout>
					</v-container>
				</v-card-text>
			</v-card>
			<v-card tile v-if="route == 'profile'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Profile</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text style="max-height: 100%;">
					<v-card tile style="max-width: 400px;margin: auto;">
						<v-toolbar color="grey lighten-4" light flat>
							<v-toolbar-title>Update profile</v-toolbar-title>
							<v-spacer></v-spacer>
						</v-toolbar>
						<v-card-text>
							<v-list>
							<v-list-item link href="https://gravatar.com" target="_blank">
								<v-list-item-avatar>
									<v-img :src="gravatar"></v-img>
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title>Edit thumbnail with Gravatar</v-list-item-title>
								</v-list-item-content>
								<v-list-item-icon>
									<v-icon>mdi-open-in-new</v-icon>
								</v-list-item-icon>
							</v-list-item>
							</v-list>
							<v-text-field :value="profile.display_name" @change.native="profile.display_name = $event.target.value" label="Display Name"></v-text-field>
							<v-text-field :value="profile.email" @change.native="profile.email = $event.target.value" label="Email"></v-text-field>
							<v-text-field :value="profile.login" @change.native="profile.login = $event.target.value" label="Username" readonly disabled></v-text-field>
							<v-text-field :value="profile.new_password" @change.native="profile.new_password = $event.target.value" type="password" label="New Password" hint="Leave empty to keep current password." persistent-hint></v-text-field>
							<v-alert :value="true" type="error" v-for="error in profile.errors" class="mt-5">{{ error }}</v-alert>
							<v-alert :value="true" type="success" v-show="profile.success" class="mt-5">{{ profile.success }}</v-alert>
							
							<v-flex xs12 mt-5>
								<v-btn color="primary" dark @click="updateAccount()">Save Account</v-btn>
							</v-flex>
					</v-card-text>
					</v-card>
				</v-card-text>
			</v-card>
			<v-card tile v-if="route == 'accounts'" flat>
			<v-sheet v-show="dialog_account.step == 1">
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ accounts.length }} accounts</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items v-if="role == 'administrator'">
						<v-btn text @click="dialog_new_account.show = true">Add account <v-icon dark>add</v-icon></v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text>
					<v-row class="ma-0 pa-0">
						<v-col class="ma-0 pa-0"></v-col>
						<v-col class="ma-0 pa-0"sm="12" md="4">
						<v-text-field
							v-model="account_search"
							autofocus
							append-icon="search"
							label="Search"
							single-line
							clearable
							hide-details
						></v-text-field>
						</v-col>
					</v-row>
				</v-card-text>
					<v-data-table
						:headers="[
							{ text: 'Name', value: 'name' },
							{ text: 'Users', value: 'metrics.users', width: '100px' },
							{ text: 'Sites', value: 'metrics.sites', width: '100px' },
							{ text: 'Domains', value: 'metrics.domains', width: '100px' }]"
						:items="accounts"
						:search="account_search"
						:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
					>
					<template v-slot:body="{ items }">
						<tbody>
						<tr v-for="item in items" :key="item.account_id" @click="goToPath( `/account/accounts/${item.account_id}`)" style="cursor:pointer;">
							<td>{{ item.name }}</td>
							<td><span v-show="item.metrics.users != '' && item.metrics.users != null">{{ item.metrics.users }}</span></td>
							<td><span v-show="item.metrics.sites != '' && item.metrics.sites != null">{{ item.metrics.sites }}</span></td>
							<td><span v-show="item.metrics.domains != '' && item.metrics.domains != null">{{ item.metrics.domains }}</span></td>
						</tr>
						</tbody>
					</template>
					</v-data-table>
				</v-sheet>
				<v-sheet v-show="dialog_account.step == 2">
				<v-card flat v-if="dialog_account.show && typeof dialog_account.records.account == 'object'">
					<v-toolbar flat color="grey lighten-4">
						<v-toolbar-title>{{ dialog_account.records.account.name }}</v-toolbar-title>
						<div class="flex-grow-1"></div>
						<v-toolbar-items>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-btn text small @click="dialog_configure_defaults.show = true" v-on="on"><v-icon dark>mdi-clipboard-check-outline</v-icon></v-btn>
								</template><span>Configure Defaults</span>
							</v-tooltip>
							<v-divider vertical class="mx-1" inset></v-divider>
							<v-btn text href="/account/accounts" @click.prevent="goToPath( '/account/accounts' )"><v-icon>mdi-arrow-left</v-icon> Back</v-btn>
						</v-toolbar-items>
					</v-toolbar>
					<v-tabs v-model="account_tab" background-color="primary" dark>
						<v-tab>
							{{ dialog_account.records.users.length }} Users
							<v-icon size="20" class="ml-1">mdi-account</v-icon>
						</v-tab>
						<v-tab>
							{{ dialog_account.records.sites.length }} Sites
							<v-icon size="20" class="ml-1">mdi-folder-multiple</v-icon>
						</v-tab>
						<v-tab>
							{{ dialog_account.records.domains.length }} Domains
							<v-icon size="20" class="ml-1">mdi-library-books</v-icon>
						</v-tab>
						<v-tab>
							Timeline
							<v-icon size="20" class="ml-1">mdi-timeline-text-outline</v-icon>
						</v-tab>
						<v-tab v-show="role == 'administrator' || dialog_account.records.owner">
							Plan <v-icon size="20" class="ml-1">mdi-chart-donut</v-icon>
						</v-tab>
					</v-tabs>
					<v-tabs-items v-model="account_tab">
					<v-tab-item :transition="false" :reverse-transition="false">
						<v-toolbar dense flat color="grey lighten-4" v-show="role == 'administrator' || dialog_account.records.owner">
							<div class="flex-grow-1"></div>
							<v-toolbar-items>
								<v-btn text @click="dialog_account.new_invite = true">New Invite <v-icon dark>add</v-icon></v-btn>
							</v-toolbar-items>
						</v-toolbar>
							<v-card v-show="dialog_account.new_invite == true" class="mb-3">
								<v-toolbar flat dense dark color="primary" id="new_invite">
								<v-btn icon dark @click.native="dialog_account.new_invite = false">
									<v-icon>close</v-icon>
								</v-btn>
								<v-toolbar-title>New Invitation</v-toolbar-title>
								<v-spacer></v-spacer>
								</v-toolbar>
								<v-card-text>
								<v-container>
								<v-layout row wrap>
									<v-flex xs12>
										<v-text-field label="Email" :value="dialog_account.new_invite_email" @change.native="dialog_account.new_invite_email = $event.target.value"></v-text-field>
									</v-flex>
									<v-flex xs12 text-right pa-0 ma-0>
										<v-btn color="primary" dark @click="sendAccountInvite()">
											Send Invite
										</v-btn>
									</v-flex>
									</v-flex>
								</v-layout>
								</v-container>
								</v-card-text>
							</v-card>
							<v-data-table
								v-show="typeof dialog_account.records.users == 'object' && dialog_account.records.users.length > 0"
								:headers='[{"text":"Name","value":"name"},{"text":"Email","value":"email"},{"text":"","value":"level"},{"text":"","value":"actions"}]'
								:items="dialog_account.records.users"
								:sort-by='["level","name"]'
								sort-desc
								:items-per-page="-1"
								hide-default-footer
							>
							<template v-slot:item.actions="{ item }">
							<v-btn text icon color="pink" @click="removeAccountAccess( item.user_id )" v-if="role == 'administrator' || dialog_account.records.owner && item.level != 'Owner'">
								<v-icon>mdi-delete</v-icon>
							</v-btn>
							</template>
							</v-data-table>
							<v-data-table
								v-show="typeof dialog_account.records.invites == 'object' && dialog_account.records.invites.length > 0"
								:headers='[{"text":"Email","value":"email"},{"text":"Created","value":"created_at"},{"text":"","value":"actions"}]'
								:items="dialog_account.records.invites"
								:items-per-page="-1"
								hide-default-footer
								hide-default-header
							>
							<template v-slot:header>
								<tr>
								<td colspan="3" style="padding:0px;padding-top:16px;">
									<v-divider></v-divider>
									<v-subheader>Invites</v-subheader>
								</td>
								</tr>
							</template>
							<template v-slot:item.created_at="{ item }">
							{{ item.created_at | pretty_timestamp }}
							</template>
							<template v-slot:item.actions="{ item }">
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-btn text icon v-on="on" @click="copyInviteLink( item.account_id, item.token )"><v-icon dark>mdi-link-variant</v-icon></v-btn>
								</template><span>Copy Invite Link</span>
							</v-tooltip>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-btn text icon color="pink" @click="deleteInvite( item.invite_id )" v-on="on" v-if="role == 'administrator'"><v-icon dark>mdi-delete</v-icon></v-btn>
								</template><span>Delete Invite</span>
							</v-tooltip>
							</template>
							</v-data-table>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
							<v-data-table
								v-show="typeof dialog_account.records.sites == 'object' && dialog_account.records.sites.length > 0"
								:headers='[{"text":"Sites","value":"name"},{"text":"Storage","value":"storage"},{"text":"Visits","value":"visits"}]'
								:items="dialog_account.records.sites"
								:items-per-page="-1"
								hide-default-footer
							>
							<template v-slot:item.storage="{ item }">
								{{ item.storage | formatGBs }}GB
							</template>
							<template v-slot:item.visits="{ item }">
								{{ item.visits | formatLargeNumbers }}
							</template>
							<template v-slot:body.append>
								<tr>
								<td class="text-right">
									Totals: 
								</td>
								<td>
									{{ dialog_account.records.account.plan.usage.storage | formatGBs }}GB
								</td>
								<td>
									{{ dialog_account.records.account.plan.usage.visits | formatLargeNumbers }}
								</td>
								</tr>
							</template>
							</v-data-table>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
							<v-data-table
								v-show="typeof dialog_account.records.domains == 'object' && dialog_account.records.domains.length > 0"
								:headers='[{"text":"Domain","value":"name"}]'
								:items="dialog_account.records.domains"
								:items-per-page="-1"
								hide-default-footer
							>
							<template v-slot:item.actions="{ item }">
							<v-btn text icon color="pink" v-if=>
								<v-icon>mdi-delete</v-icon>
							</v-btn>
							</template>
							</v-data-table>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
						<v-data-table
							:headers="header_timeline"
							:items="dialog_account.records.timeline"
							:footer-props="{ itemsPerPageOptions: [50,100,250,{'text':'All','value':-1}] }"
							class="timeline"
							v-if="typeof dialog_account.records.timeline != 'undefined' || dialog_account.records.timeline != null"
						>
						<template v-slot:body="{ items }">
						<tbody>
						<tr v-for="item in items">
							<td class="justify-center">{{ item.created_at | pretty_timestamp_epoch }}</td>
							<td class="justify-center">{{ item.author }}</td>
							<td class="justify-center">{{ item.name }}</td>
							<td class="justify-center py-3" v-html="item.description"></td>
							<td width="170px;">
								<v-btn text icon @click="dialog_log_history.show = false; editLogEntry(item.websites, item.process_log_id)" v-if="role == 'administrator'">
									<v-icon small>edit</v-icon>
								</v-btn>
								{{ item.websites.map( site => site.name ).join(" ") }}
							</td>
						</tr>
						</tbody>
						</template>
						</v-data-table>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
					<v-toolbar color="grey lighten-4" dense light flat>
						<v-spacer></v-spacer>
							<v-toolbar-items v-show="role == 'administrator'">
								<v-btn text @click="modifyPlan()">Edit Plan <v-icon dark small>edit</v-icon></v-btn>
							</v-toolbar-items>
						</v-toolbar>
					<v-card flat>
					<div v-if="typeof dialog_account.records.account.plan == 'object' && dialog_account.records.account.plan != null">
						<v-card-text class="body-1">
						<v-row>
						<v-col>
						<v-layout align-center justify-left row/>
							<div style="padding: 10px 10px 10px 20px;">
								<v-progress-circular :size="50" :value="( dialog_account.records.account.plan.usage.storage / ( dialog_account.records.account.plan.limits.storage * 1024 * 1024 * 1024 ) ) * 100 | formatPercentage" color="primary"><small>{{ ( dialog_account.records.account.plan.usage.storage / ( dialog_account.records.account.plan.limits.storage * 1024 * 1024 * 1024 ) ) * 100 | formatPercentage }}</small></v-progress-circular>
							</div>
							<div style="line-height: 0.85em;">
								Storage <br /><small>{{ dialog_account.records.account.plan.usage.storage | formatGBs }}GB / {{ dialog_account.records.account.plan.limits.storage }}GB</small><br />
							</div>
							<div style="padding: 10px 10px 10px 20px;">
								<v-progress-circular :size="50" :value="( dialog_account.records.account.plan.usage.visits / dialog_account.records.account.plan.limits.visits * 100 ) | formatPercentage" color="primary"><small>{{ ( dialog_account.records.account.plan.usage.visits / dialog_account.records.account.plan.limits.visits ) * 100 | formatPercentage }}</small></v-progress-circular>
							</div>
							<div style="line-height: 0.85em;">
								Visits <br /><small>{{ dialog_account.records.account.plan.usage.visits | formatLargeNumbers }} / {{ dialog_account.records.account.plan.limits.visits | formatLargeNumbers }}</small><br />
							</div>
							<div style="padding: 10px 10px 10px 20px;">
								<v-progress-circular :size="50" :value="( dialog_account.records.account.plan.usage.sites / dialog_account.records.account.plan.limits.sites * 100 ) | formatPercentage" color="blue darken-4"><small>{{ ( dialog_account.records.account.plan.usage.sites / dialog_account.records.account.plan.limits.sites * 100 ) | formatPercentage }}</small></v-progress-circular>
							</div>
							<div  style="line-height: 0.85em;">
								Sites <br /><small>{{ dialog_account.records.account.plan.usage.sites }} / {{ dialog_account.records.account.plan.limits.sites }}</small><br />
							</div>
						</v-layout>
						</v-col>
						<v-col class="text-center">
							<span class="text-uppercase caption">Estimate based on current usage</span><br />
							<span class="display-1 font-weight-thin" v-html="plan_usage_estimate"></span><br />
							<span>
							<v-dialog v-model="dialog_breakdown" width="600">
								<template v-slot:activator="{ on, attrs }">
									<a v-bind="attrs" v-on="on">See breakdown</a>
								</template>
								<v-card>
								<v-toolbar flat dark color="primary">
									<v-btn icon dark @click.native="dialog_breakdown = false">
										<v-icon>close</v-icon>
									</v-btn>
									<v-toolbar-title>Plan Estimate Breakdown</v-toolbar-title>
									<v-spacer></v-spacer>
								</v-toolbar>
								<v-card-text>
									<v-simple-table>
									<template v-slot:default>
									<thead>
										<tr>
										<th class="text-left">
											Type
										</th>
										<th class="text-left">
											Name
										</th>
										<th class="text-left">
											Quantity
										</th>
										<th class="text-left">
											Price
										</th>
										<th class="text-left">
											Total
										</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Plan</td>
											<td>{{ dialog_account.records.account.plan.name }}</td>
											<td>1</td>
											<td class="text-right">${{ dialog_account.records.account.plan.price }}</td>
											<td class="text-right">${{ dialog_account.records.account.plan.price }}</td>
										</tr>
										<tr v-if="( parseInt( dialog_account.records.account.plan.usage.sites ) - parseInt( dialog_account.records.account.plan.limits.sites ) ) >= 1">
											<td>Extra</td>
											<td>Sites</td>
											<td>{{ parseInt( dialog_account.records.account.plan.usage.sites ) - parseInt( dialog_account.records.account.plan.limits.sites ) }}</td>
											<td class="text-right">${{ plan_usage_pricing_sites }}</td>
											<td class="text-right">${{ plan_usage_pricing_sites * ( parseInt( dialog_account.records.account.plan.usage.sites ) - parseInt( dialog_account.records.account.plan.limits.sites ) ) }}</td>
										</tr>
										<tr v-if="(( parseInt( dialog_account.records.account.plan.usage.storage ) / 1024 / 1024 / 1024 ) - parseInt( dialog_account.records.account.plan.limits.storage ) ) >= 1">
											<td>Extra</td>
											<td>Storage</td>
											<td>{{ Math.round ( ( ( parseInt( dialog_account.records.account.plan.usage.storage ) / 1024 / 1024 / 1024 ) - parseInt( dialog_account.records.account.plan.limits.storage ) ) / 10 ) }}</td>
											<td class="text-right">${{ plan_usage_pricing_storage }}</td>
											<td class="text-right">${{ plan_usage_pricing_storage * Math.round ( ( ( parseInt( dialog_account.records.account.plan.usage.storage ) / 1024 / 1024 / 1024 ) - parseInt( dialog_account.records.account.plan.limits.storage ) ) / 10 ) }}</td>
										</tr>
										<tr v-if="Math.round ( ( parseInt( dialog_account.records.account.plan.usage.visits ) - parseInt( dialog_account.records.account.plan.limits.visits ) ) / parseInt ( configurations.usage_pricing.traffic.quantity ) ) >= 1">
											<td>Extra</td>
											<td>Visits</td>
											<td>{{ Math.round ( ( parseInt( dialog_account.records.account.plan.usage.visits ) - parseInt( dialog_account.records.account.plan.limits.visits ) ) / parseInt ( configurations.usage_pricing.traffic.quantity ) ) }}</td>
											<td class="text-right">${{ plan_usage_pricing_visits }}</td>
											<td class="text-right">${{ plan_usage_pricing_visits * Math.round ( ( parseInt( dialog_account.records.account.plan.usage.visits ) - parseInt( dialog_account.records.account.plan.limits.visits ) ) / parseInt ( configurations.usage_pricing.traffic.quantity ) ) }}</td>
										</tr>
										<tr v-for="item in dialog_account.records.account.plan.addons">
											<td>Addon</td>
											<td>{{ item.name }}</td>
											<td>{{ item.quantity }}</td>
											<td class="text-right">${{ item.price }}</td>
											<td class="text-right">${{ item.quantity * item.price }}</td>
										</tr>
										<tr>
											<td colspan="5" class="body-1">Total: <span v-html="plan_usage_estimate"></span></td>
										</tr>
									</tbody>
									</template>
								</v-simple-table>
								</v-card-text>
								</v-card>
							</v-dialog>	
						</v-col>
						</v-row>
						</v-card-text>
						<v-alert
							:value="true"
							type="info"
							color="primary"
						>
					<strong>{{ dialog_account.records.account.plan.name }} Plan</strong> supports up to {{ dialog_account.records.account.plan.limits.visits | formatLargeNumbers }} visits, {{ dialog_account.records.account.plan.limits.storage }}GB storage and {{ dialog_account.records.account.plan.limits.sites }} sites. Extra sites, storage and visits charged based on usage.
						</v-alert>
						</div>
						<div v-else>
						<v-alert
							:value="true"
							type="info"
							color="primary"
						>
							Development mode, no plan selected.
						</v-alert>
						</div>
						<v-data-table
							:headers='[{"text":"Name","value":"name"},{"text":"Storage","value":"Storage"},{"text":"Visits","value":"visits"}]'
							:items="dialog_account.records.usage_breakdown.sites"
							item-key="name"
							hide-default-footer
						>
						<template v-slot:body="{ items }">
						<tbody>
							<tr v-for="item in items">
								<td>{{ item.name }}</td>
								<td>{{ item.storage | formatGBs }}GB</td>
								<td>{{ item.visits }}</td>
							</tr>
							<tr>
								<td>Totals:</td>
						<td v-for="total in dialog_account.records.usage_breakdown.total" v-html="total"></td>
							</tr>
						</tbody>
						</template>
						</v-data-table>
					</v-card>
					</v-tab-item>
					</v-tabs-items>
					<div v-show="role == 'administrator'">
					<v-divider></v-divider>
					<v-subheader>Administrator Options</v-subheader>
					<v-container>
					<v-btn small depressed @click="editAccount()">
						<v-icon>edit</v-icon> Edit Account
					</v-btn>
					<v-btn small depressed color="error" @click="deleteAccount()">
						<v-icon>delete</v-icon> Delete Account
					</v-btn>
					</v-container>
					</div>
					</v-card>
				</v-sheet>
			</v-card>
			<v-card tile v-if="route == 'users'" flat>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Listing {{ users.length }} users</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
					</v-toolbar-items>
				</v-toolbar>
				<v-card-text>
					<v-row class="ma-0 pa-0">
						<v-col class="ma-0 pa-0"></v-col>
						<v-col class="ma-0 pa-0"sm="12" md="4">
						<v-text-field
							v-model="user_search"
							autofocus
							append-icon="search"
							label="Search"
							single-line
							clearable
							hide-details
						></v-text-field>
						</v-col>
					</v-row>
				</v-card-text>
					<v-data-table
						:headers="[{ text: 'Name', value: 'name' },{ text: 'Username', value: 'username' },,{ text: 'Email', value: 'email' },{ text: '', value: 'user_id', align: 'end', sortable: false }]"
						:items="users"
						:search="user_search"
						:footer-props="{ itemsPerPageOptions: [100,250,500,{'text':'All','value':-1}] }"
					>
					<template v-slot:item.user_id="{ item }">
						<v-btn text color="primary" @click="editUser( item.user_id )">Edit User</v-btn>
					</template>
					</v-data-table>
			</v-card>
			<v-dialog v-if="route == 'invite'" value="true" scrollable persistance width="500" height="300">
			<v-overlay :value="true" v-if="typeof new_invite.account.name == 'undefined'">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
			<v-card tile v-else>
				<v-toolbar color="grey lighten-4" light flat>
					<v-toolbar-title>Account <strong><span v-html="new_invite.account.name"></span></strong> contains:</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
					</v-toolbar-items>
					<template v-slot:extension>
						<v-tabs v-model="account_tab" background-color="primary" dark>
						<v-tab>
							<v-icon class="mr-1">mdi-folder-multiple</v-icon>
							{{ new_invite.account.website_count }} Sites
						</v-tab>
						<v-tab>
							<v-icon class="mr-1">mdi-library-books</v-icon>
							{{ new_invite.account.domain_count }} Domains
						</v-tab>
						</v-tabs>
					</template>
				</v-toolbar>
				<v-card-text style="height:300px;">
					<v-tabs-items v-model="account_tab">
					<v-tab-item>
						<v-data-table
							v-show="typeof new_invite.sites == 'object' && new_invite.sites.length > 0"
							:headers='[{"text":"Sites","value":"name"}]'
							:items="new_invite.sites"
							:items-per-page="-1"
							hide-default-footer
						>
						</v-data-table>
					</v-tab-item>
					<v-tab-item>
						<v-data-table
							v-show="typeof new_invite.domains == 'object' && new_invite.domains.length > 0"
							:headers='[{"text":"Domain","value":"name"}]'
							:items="new_invite.domains"
							:items-per-page="-1"
							hide-default-footer
						>
						</v-data-table>
					</v-tab-item>
					</v-tabs-items>
						</v-card-text>
						<v-divider></v-divider>
						<v-card-actions>
							<div class="flex-grow-1"></div>
							<v-btn @click="cancelInvite">Cancel</v-btn>
							<v-btn @click="acceptInvite" color="primary" dark>Accept Invite as {{ current_user_login }}</v-btn>
						</v-card-actions>
						</v-card>
						</v-dialog>
					</v-layout>
				</v-container>
				</v-card-text>
			</v-card>
			</v-container>
			<v-container fluid v-show="loading_page">
				Loading...
			</v-container>
			<v-snackbar
				:timeout="3000"
				:multi-line="true"
				v-model="snackbar.show"
				style="z-index: 9999999;"
			>
				{{ snackbar.message }}
				<v-btn dark text @click.native="snackbar.show = false">Close</v-btn>
			</v-snackbar>
		</template>
		</v-container>
		</v-main>
		<v-footer app padless :height="footer_height" class="pa-0 ma-0" style="font-size:12px;z-index:10" class="console" v-if="view_console.show == true">
		<div class="ma-0 pa-0" style="width: 100%;">
		<v-row no-gutters>
        <v-col>
		<v-card v-show="view_console.open == true" class="pa-0 ma-0" flat style="height:174px;" color="transparent">
			<v-window v-model="console">
			<v-window-item :value="0">
			</v-window-item>
			<v-window-item :value="1">
			<v-toolbar flat dense color="transparent">
			<span>Task Activity</span>
			<v-spacer></v-spacer>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn v-on="on" small icon @click.native="jobs = []; snackbar.message = 'Task activity cleared.'; snackbar.show = true">
						<v-icon>mdi-trash-can-outline</v-icon>
					</v-btn>
					</template><span>Clear Task Activity</span>
				</v-tooltip>
				<v-btn small icon @click="closeConsole()">
					<v-icon>close</v-icon>
				</v-btn>
			</v-toolbar>
			<v-card-text style="height:130px; overflow-y:scroll;" class="ma-0 pa-0">
			<v-data-table
				:headers="[{ text: 'Description', value: 'description' },
							{ text: 'Status', value: 'status' },
							{ text: 'Response', value: 'response' }]"
				:items="jobs.slice().reverse()"
				class="transparent elevation-0 pa-0 ma-0"
				hide-default-header hide-default-footer dense
			>
				<template v-slot:body="{ items }">
				<tbody>
				<tr>
					<td class="ma-0 pa-0">
				<v-list dense flat class="transparent ma-0 pa-0">
				<v-list-item-group>
					<template v-for="(item, index) in items">
					<v-list-item :key="item.job_id" @click="viewJob( item.job_id )">
						<template v-slot:default="{ active, toggle }">
						<v-list-item-content>
							<v-list-item-subtitle>
								<v-chip v-if="item.status == 'done'" x-small label color="green" dark class="mr-2">Done</v-chip>
								<v-chip v-else-if="item.status == 'error'" x-small label color="red" dark class="mr-2">Error</v-chip>
								<v-chip v-else x-small label color="primary" dark class="mr-2">Running</v-chip>
								{{ item.description }}
								<small v-if="typeof item.stream == 'object'" class="ml-2">{{ item.stream.slice(-1)[0] }}</small>
							</v-list-item-subtitle>
						</v-list-item-content>
						<v-list-item-icon class="ma-0" v-if="item.status != 'done' && item.status != 'error'">
							<v-btn style="margin-top:2.5px" text x-small @click.stop="killCommand(item.job_id)">Cancel</v-btn>
						</v-list-item-icon>
						</template>
					</v-list-item>
					</template>
				</v-list-item-group>
				</v-list>
				</td>
				</tr>
				</tbody>
				</template>
			</v-data-table>
			</v-card-text>
			</v-window-item>
			<v-window-item :value="4">
			<v-toolbar flat dense color="transparent">
			<span>Task - {{ dialog_job.task.description }}</span>
			<v-spacer></v-spacer>
				<v-btn text tile small @click.native="console = 1">
					<v-icon>mdi-arrow-left</v-icon> Back to Task Activity
				</v-btn>
				<a ref="export_task" href="#"></a>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn v-on="on" small icon @click.native="exportTaskResults()">
						<v-icon>mdi-file-download</v-icon>
					</v-btn>
					</template><span>Export Results</span>
				</v-tooltip>
				<v-btn small icon @click="closeConsole()">
					<v-icon>close</v-icon>
				</v-btn>
			</v-toolbar>
			<v-card-text style="height:130px; overflow-y:scroll; transform: scaleY(-1);" class="ma-0 py-0 px-5">
				<v-layout row wrap>
					<v-flex xs12 pa-2>
					<v-card text width="100%" class="transparent elevation-0">
						<small mv-1 style="display: block; transform: scaleY(-1);"><div v-for="s in dialog_job.task.stream">{{ s }}</div></small>
		</v-card>
					</v-flex>
				</v-layout>
			</v-card-text>
			</v-window-item>
			<v-window-item :value="2">
			<v-toolbar flat dense color="transparent">
			<span class="mr-2">Filters</span>
			<v-autocomplete
				v-model="applied_site_filter"
				@input="filterSites"
				:items="site_filters"
				ref="applied_site_filter"
				return-object
				item-text="title"
				label="Select Theme and/or Plugin"
				class="siteFilter mx-1"
				chips
				allow-overflow
				small-chips
				solo
				multiple
				hide-details
				hide-selected
				deletable-chips
				dense
				height="24"
				style="max-width: 420px"
				:menu-props="{ closeOnContentClick:true, openOnClick: false }"
			>
			</v-autocomplete>
			<v-spacer></v-spacer>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn v-on="on" small icon @click.native="clearFilters(); snackbar.message = 'Filters cleared.'; snackbar.show = true">
						<v-icon>mdi-trash-can-outline</v-icon>
					</v-btn>
					</template><span>Clear Filters</span>
				</v-tooltip>
				<v-btn small icon @click="closeConsole()">
					<v-icon>close</v-icon>
				</v-btn>
			</v-toolbar>
			<v-card-text style="height:130px; overflow-y:scroll;" class="ma-0 py-0 px-5">
		<v-layout row>
			<v-flex xs6 px-1>
				 <v-autocomplete
					v-model="applied_site_filter_version"
					v-for="filter in site_filter_version"
					@input="filterSites"
					ref="applied_site_filter_version"
					:items="filter.versions"
					:key="filter.name"
					:label="'Select Version for '+ filter.name"
					class="mb-1"
					item-text="name"
					return-object
					chips
					small-chips
					solo
					multiple
					hide-details
					hide-selected
					deletable-chips
					dense
				 >
				 <template v-slot:item="data">
						<div class="v-list-item__title"><strong>{{ data.item.name }}</strong>&nbsp;<span>({{ data.item.count }})</span></div>
				 </template>
				</v-autocomplete>
			</v-flex>
			<v-flex xs6 px-1>
				<v-autocomplete
					v-model="applied_site_filter_status"
					v-for="filter in site_filter_status"
					:items="filter.statuses"
					:key="filter.name"
					:label="'Select Status for '+ filter.name"
					class="mb-1"
					@input="filterSites"
					item-text="name"
					return-object
					chips
					small-chips
					solo
					multiple
					hide-details
					hide-selected
					deletable-chips
					dense
				>
				<template slot="item" slot-scope="data">
					<div class="v-list-item__title"><strong>{{ data.item.name }}</strong>&nbsp;<span>({{ data.item.count }})</span></div>
				</template>
				</v-autocomplete>
			</v-flex>
			</v-layout>
			</v-window-item>
			<v-window-item :value="3">
			<v-toolbar flat dense color="transparent">
			<span class="mr-2">Bulk Tools</span>
			<v-select
				v-model="dialog_bulk.environment_selected"
				:items='[{"name":"Production Environment","value":"Production"},{"name":"Staging Environment","value":"Staging"}]'
				item-text="name"
				item-value="value"
				@change="triggerEnvironmentUpdate()"
				class="mx-1 mt-6"
				solo
				dense
				chips
				small-chips
				style="max-width:240px;">
			</v-select>
			<v-autocomplete
				v-model="sites_selected"
				:items="sites"
				item-text="name"
				return-object
				chips
				small-chips
				dense
				solo
				label="Search"
				multiple
				class="mx-1 mt-6"
				style="max-width:240px;"
			>
			<template v-slot:selection="{ item, index }">
				<span v-if="index === 0" class="v-chip--select v-chip v-chip--clickable v-chip--no-color theme--light v-size--small"><span class="v-chip__content">{{ sites_selected.length }} sites selected</span></span>
			</template>
			</v-autocomplete>
			<v-btn small text v-show="filterCount" @click="sites_selected = sites.filter( s => s.filtered )">
				Select {{ sites.filter( s => s.filtered ).length }} sites in applied filters
			</v-btn>
			<v-btn small text @click="sites_selected = sites">
				Select all {{ sites.length }} sites
			</v-btn>
			<v-spacer></v-spacer>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="addThemeBulk()" v-on="on">
						<v-icon>add</v-icon>
					</v-btn>
					</template>
					<span>Add theme</span>
				</v-tooltip>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="addPluginBulk()" v-on="on">
						<v-icon>add</v-icon>
					</v-btn>
					</template>
					<span>Add plugin</span>
				</v-tooltip>
				<v-tooltip top v-if="role == 'administrator'">
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="showLogEntryBulk()" v-on="on">
						<v-icon>mdi-checkbox-marked</v-icon>
					</v-btn>
					</template>
					<span>New Log Entry</span>
				</v-tooltip>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="bulkactionLaunch()" v-on="on">
						<v-icon>mdi-open-in-new</v-icon>
					</v-btn>
					</template>
					<span>Open websites in browser</span>
				</v-tooltip>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="bulkSyncSites()" v-on="on">
						<v-icon>mdi-sync</v-icon>
					</v-btn>
					</template>
					<span>Manual sync website details</span>
				</v-tooltip>
				<v-tooltip top>
					<template v-slot:activator="{ on }">
					<v-btn small icon @click="sites_selected = []; snackbar.message = 'Selections cleared.'; snackbar.show = true" v-on="on">
						<v-icon>mdi-trash-can-outline</v-icon>
					</v-btn>
					</template><span>Clear Selections</span>
				</v-tooltip>
				<v-btn small icon @click="closeConsole()">
					<v-icon>close</v-icon>
				</v-btn>
			</v-toolbar>
				<v-card-text style="height:130px; overflow-y:scroll;" class="ma-0 py-0 px-5">
				<v-row>
				<v-col cols="12" md="4" class="py-0 my-0">
					<small>Common Scripts</small><br />
					<v-tooltip top>
						<template v-slot:activator="{ on }">
						<v-btn small icon @click="viewApplyHttpsUrlsBulk()" v-on="on">
							<v-icon>launch</v-icon>
						</v-btn>
						</template><span>Apply HTTPS Urls</span>
					</v-tooltip>
					<v-tooltip top>
						<template v-slot:activator="{ on }">
						<v-btn small icon @click="siteDeployBulk()" v-on="on">
							<v-icon>loop</v-icon>
						</v-btn>
						</template><span>Deploy Defaults</span>
					</v-tooltip>
					<v-tooltip top>
						<template v-slot:activator="{ on }">
						<v-btn small icon @click="toggleSiteBulk()" v-on="on">
							<v-icon>mdi-toggle-switch</v-icon>
						</v-btn>
						</template><span>Toggle Site</span>
					</v-tooltip><br />
					<small>Other Scripts</small><br />
					<v-tooltip top dense v-for="recipe in recipes.filter( r => r.public == 1 )">
						<template v-slot:activator="{ on }">
						<v-btn small icon @click="runRecipeBulk( recipe.recipe_id )" v-on="on">
							<v-icon>mdi-script-text-outline</v-icon>
						</v-btn>
						</template><span>{{ recipe.title }}</span>
					</v-tooltip><br />
					<small><span v-show="sites_selected.length > 0">Selected sites: </span>
						<span v-for="site in sites_selected" style="display: inline-block;" v-if="dialog_bulk.environment_selected == 'Production' || dialog_bulk.environment_selected == 'Both'">{{ site.site }}&nbsp;</span>
						<span v-for="site in sites_selected" style="display: inline-block;" v-if="dialog_bulk.environment_selected == 'Staging' || dialog_bulk.environment_selected == 'Both'">{{ site.site }}-staging&nbsp;</span>
					</small>
				</v-col>
				<v-col cols="12" md="8" class="py-0 my-0">
					<v-textarea
							auto-grow
							solo
							rows="4"
							dense
							hint="Custom bash script or WP-CLI commands"
							persistent-hint
							:value="custom_script" 
							@change.native="custom_script = $event.target.value"
							spellcheck="false"
							class="code"
						>
						<template v-slot:append-outer>
							<v-btn small color="primary" dark @click="runCustomCodeBulk()">Run Custom Code</v-btn>
						</template>
					</v-textarea>
		</v-col>
		</v-row>
			</v-card>
			</v-window-item>
			</v-window>
		</v-card>
		</v-col>
		</v-row>
		<v-row no-gutters justify="center">
        <v-col cols="11">
		<v-tooltip top>
			<template v-slot:activator="{ on }">
				<v-btn text tile small @click="toggleConsole( 1 )" v-on="on">
					<v-icon x-small>mdi-cogs</v-icon> Task Activity
					<div v-show="runningJobs"><v-chip x-small label color="secondary" class="pa-1 ma-2">{{ runningJobs }} Running</v-chip> <v-progress-circular indeterminate class="ml-2" size="16" width="2"></v-progress-circular></div>
					<div v-show="! runningJobs && completedJobs"><v-chip x-small label color="secondary" class="pa-1 ma-2">{{ completedJobs }} Completed</v-chip></div>
				</v-btn>
			</template><span>View Task Activity</span>
		</v-tooltip>
		<v-tooltip top>
			<template v-slot:activator="{ on }">
				<v-btn text tile small @click="toggleConsole( 2 )" v-on="on">
					<v-icon x-small>mdi-filter</v-icon> Site Filters
					<div v-show="filterCount"><v-chip x-small label color="secondary" class="pa-1 ma-2">{{ filterCount }} Applied</v-chip></div>
				</v-btn>
			</template><span>View Filters</span>
		</v-tooltip>
		<v-tooltip top>
			<template v-slot:activator="{ on }">
				<v-btn text tile small @click="toggleConsole( 3 )" v-on="on">
					<v-icon x-small>mdi-settings</v-icon> Site Bulk Tools
					<div v-show="sites_selected.length > 0"><v-chip x-small label color="secondary" class="pa-1 ma-2">{{ sites_selected.length }} Selected</v-chip></div>
				</v-btn>
			</template><span>View Bulk Tools</span>
		</v-tooltip>
		</v-col>
		<v-col cols="1" class="text-right">
		<v-btn icon tile small @click="view_console.show = false" v-show="view_console.open == false">
			<v-icon x-small>close</v-icon>
		</v-btn>
		</v-col>
		</v-row>
		</div>
		</v-footer>
	</v-app>
</div>
<?php if ( substr( $_SERVER['SERVER_NAME'], -4) == 'test' ) { ?>
<script src="/wp-content/plugins/captaincore/public/js/vue.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/qs.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/axios.min.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/vuetify.min.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/vue-upload-component.js"></script>
<?php } else { ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qs@6.9.1/dist/qs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@0.19.0/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.3.14/dist/vuetify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-upload-component@2.8.20/dist/vue-upload-component.js"></script>
<?php } ?>
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/moment.min.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/frappe-charts.js"></script>
<script src="/wp-content/plugins/captaincore/public/js/core.js"></script>
<script>
ajaxurl = "/wp-admin/admin-ajax.php"
Vue.component('file-upload', VueUploadComponent)
new Vue({
	el: '#app',
	vuetify: new Vuetify({
		theme: {
			themes: {
				light: <?php echo json_encode( ( new CaptainCore\Configurations )->colors() ); ?>,
			},
		},
	}),
	data: {
		colors: { 
			primary: false,
			secondary: false,
			accent: false,
			error: false,
			info: false,
			success: false,
			warning: false,
		},
		files: {
			html: 'mdi-language-html5',
			js: 'mdi-nodejs',
			json: 'mdi-code-json',
			md: 'mdi-language-markdown',
			pdf: 'mdi-file-pdf',
			png: 'mdi-file-image',
			txt: 'mdi-file-document-outline',
			xls: 'mdi-file-excel',
			jpg: 'mdi-file-image',
			gif: 'mdi-file-image',
			php: 'mdi-file-code',
		},
		configurations: <?php echo json_encode( ( new CaptainCore\Configurations )->get() ); ?>,
		configurations_loading: true,
		footer_height: "28px",
		login: { user_login: "", user_password: "", errors: "", loading: false, lost_password: false, message: "" },
		wp_nonce: "",
		footer: <?php echo captaincore_footer_content_extracted(); ?>,
		drawer: null,
		billing_link: "<?php echo get_field( 'billing_link', 'option' ); ?>",
		home_link: "<?php echo home_url(); ?>",
		remote_upload_uri: "<?php echo get_option( 'options_remote_upload_uri' ); ?>",
		loading_page: true,
		expanded: [],
		accounts: [],
		account_tab: null,
		modules: { dns: <?php if ( defined( "CONSTELLIX_API_KEY" ) and defined( "CONSTELLIX_SECRET_KEY" ) ) { echo "true"; } else { echo "false"; } ?> },
		dialog_bulk: { show: false, tabs_management: "tab-Sites", environment_selected: "Production" },
		dialog_job: { show: false, task: {} },
		dialog_breakdown: false,
		dialog_captures: { site: {}, pages: [{ page: ""}], capture: { pages: [] }, image_path:"", selected_page: "", captures: [], mode: "screenshot", loading: true, show: false, show_configure: false },
		dialog_delete_user: { show: false, site: {}, users: [], username: "", reassign: {} },
		dialog_apply_https_urls: { show: false, site_id: "", site_name: "", sites: [] },
		dialog_copy_site: { show: false, site: {}, options: [], destination: "" },
		dialog_edit_site: { show: false, show_vars: false, loading: false, site: {
				environments: [
					{"environment": "Production", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" },
					{"environment": "Staging", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" }
				],
			},
		},
		dialog_new_domain: { show: false, domain: { name: "", account_id: "" }, loading: false, errors: [] },
		dialog_configure_defaults: { show: false, loading: false },
		dialog_domain: { show: false, show_import: false, import_json: "", domain: {}, records: [], results: [], errors: [], loading: true, saving: false, step: 1 },
		dialog_backup_snapshot: { show: false, site: {}, email: "<?php echo $user->user_email; ?>", current_user_email: "<?php echo $user->user_email; ?>", filter_toggle: true, filter_options: [] },
		dialog_file_diff: { show: false, response: "", loading: false, file_name: "" },
		dialog_launch: { show: false, site: {}, domain: "" },
		dialog_toggle: { show: false, site_name: "", site_id: "" },
		dialog_mailgun: { show: false, site: {}, response: { items: [], pagination: [] }, loading: false, pagination: {} },
		dialog_migration: { show: false, sites: [], site_name: "", site_id: "", update_urls: true, backup_url: "" },
		dialog_modify_plan: { show: false, site: {}, hosting_plans: [], selected_plan: "", plan: { limits: {}, addons: [] }, customer_name: "", interval: "12" },
		dialog_theme_and_plugin_checks: { show: false, site: {}, loading: false },
		dialog_update_settings: { show: false, environment: {}, themes: [], plugins: [], loading: false },
		dialog_fathom: { show: false, site: {}, environment: {}, loading: false, editItem: false, editedItem: {}, editedIndex: -1 },
		dialog_mailgun_config: { show: false, loading: false },
		dialog_account: { show: false, records: { account: { defaults: { recipes: [] } } }, new_invite: false, new_invite_email: "", step: 1 },
		dialog_new_account: { show: false, name: "", records: {} },
		dialog_user: { show: false, user: {}, errors: [] },
		dialog_request_site: { show: false, request: { name: "", account_id: "", notes: "" } },
		requested_sites: <?php echo json_encode( ( new CaptainCore\User )->fetch_requested_sites() ); ?>,
		new_invite: { account: {}, records: {} },
		new_account: { password: "" },
		timeline_logs: [],
		route_path: "",
		route: "",
		routes: {
			'/account': '',
			'/account/login': 'login',
			'/account/sites': 'sites',
			'/account/dns': 'dns',
			'/account/health': 'health',
			'/account/keys': 'keys',
			'/account/defaults': 'defaults',
			'/account/configurations': 'configurations',
			'/account/profile' : 'profile',
			'/account/users': 'users',
			'/account/accounts': 'accounts',
			'/account/handbook': 'handbook',
			'/account/cookbook': 'cookbook',
		},
		selected_nav: "",
		querystring: window.location.search,
		page: 1,
		socket: "<?php echo captaincore_fetch_socket_address() . "/ws"; ?>",
		timezones: <?php echo json_encode( timezone_identifiers_list() ); ?>,
		jobs: [],
		keys: [],
		defaults: [],
		custom_script: "",
		recipes: [],
		processes: [],
		current_user_email: "<?php echo $user->user_email; ?>",
		current_user_login: "<?php echo $user->user_login; ?>",
		current_user_display_name: "<?php echo $user->display_name; ?>",
		profile: { first_name: "<?php echo $user->first_name; ?>", last_name: "<?php echo $user->last_name; ?>", email: "<?php echo $user->user_email; ?>", login: "<?php echo $user->user_login; ?>", display_name: "<?php echo $user->display_name; ?>", new_password: "", errors: [] },
		<?php if ( current_user_can( "administrator" ) ) { ?>
		role: "administrator",
		dialog_new_log_entry: { show: false, sites: [], site_name: "", process: "", description: "" },
		dialog_edit_log_entry: { show: false, site_name: "", log: {} },
		dialog_log_history: { show: false, logs: [], pagination: {} },
		dialog_handbook: { show: false, process: {} },
		dialog_key: { show: false, key: {} },
		new_process: { show: false, name: "", time_estimate: "", repeat_interval: "as-needed", repeat_quantity: "", roles: "", description: "" },
		new_key: { show: false, title: "", key: "" },
		dialog_edit_process: { show: false, process: {} },
		process_roles: <?php echo ( ! empty( get_option('captaincore_process_roles') ) ? get_option('captaincore_process_roles') : "[]" ); ?>,
		dialog_new_site: {
			provider: "kinsta",
			show: false,
			show_vars: false,
			environment_vars: [],
			saving: false,
			key: "",
			site: "",
			domain: "",
			errors: [],
			shared_with: [],
			account_id: "",
			customer_id: "",
			environments: [
				{"environment": "Production", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" },
				{"environment": "Staging", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" }
			],
		},
		shared_with: [],
		<?php } else { ?>
		role: "",
		dialog_new_site: false,
		shared_with: [],<?php } ?>
		header_timeline: [
			{"text":"Date","value":"date","sortable":false,"width":"220"},
			{"text":"Done by","value":"done-by","sortable":false,"width":"135"},
			{"text":"Name","value":"name","sortable":false,"width":"165"},
			{"text":"Notes","value":"notes","sortable":false},
			{"text":"","value":"","sortable":false},
		],
		domains: [],
		domains_loading: true,
		sites_loading: true,
		domain_search: "",
		account_search: "",
		new_recipe: { show: false, title: "", content: "", public: 1 },
		dialog_cookbook: { show: false, recipe: {}, content: "" },
		dialog_site: { loading: true, step: 1, backup_step: 1, environment_selected: { environment_id: "0", quicksave_panel: [], plugins:[], themes: [], core: "", screenshots: [], users_selected: [], users: "Loading", address: "", capture_pages: [], environment: "Production", environment_label: "Production Environment", stats: "Loading", plugins_selected: [], themes_selected: [], loading_plugins: false, loading_themes: false }, site: { name: "", site: "", screenshots: {}, timeline: [], environments: [], users: [], timeline: [], update_log: [], tabs: "tab-Site-Management", tabs_management: "tab-Info", account: { plan: "Loading" }  } },
		dialog_site_request: { show: false, request: {} },
		dialog_edit_account: { show: false, account: {} },
		roles: [{ name: "Subscriber", value: "subscriber" },{ name: "Contributor", value: "contributor" },{ name: "Author", value: "author" },{ name: "Editor", value: "editor" },{ name: "Administrator", value: "administrator" }],
		new_plugin: { show: false, sites: [], site_name: "", environment_selected: "", loading: false, tabs: null, page: 1, search: "", api: {} },
		new_theme: { show: false, sites: [], site_name: "", environment_selected: "", loading: false, tabs: null, page: 1, search: "", api: {} },
		bulk_edit: { show: false, site_id: null, type: null, items: [] },
		upload: [],
		console: 0,
		view_console: { show: false, open: false },
		view_timeline: false,
		search: null,
		users_search: "",
		advanced_filter: false,
		business_name: "<?php echo $business_name; ?>",
		business_link: "<?php echo $business_link; ?>",
		sites_selected: [],
		sites_filtered: [],
		site_selected: null,
		site_filters: <?php echo json_encode( ( new CaptainCore\Environments )->filters() ); ?>,
		site_filter_version: null,
		site_filter_status: null,
		sort_direction: "asc",
		toggle_site: true,
		toggle_site_sort: null,
		toggle_site_counter: { key: "", count: 0 },
		sites: [],
		users: [],
		user_search: "",
		header_themes: [
			{ text: 'Name', value: 'title' },
			{ text: 'Slug', value: 'name' },
			{ text: 'Version', value: 'version' },
			{ text: 'Status', value: 'status', width: "100px" },
			{ text: 'Actions', value: 'actions', width: "90px", sortable: false }
		],
		header_plugins: [
			{ text: 'Name', value: 'title' },
			{ text: 'Slug', value: 'name' },
			{ text: 'Version', value: 'version' },
			{ text: 'Status', value: 'status', width: "100px" },
			{ text: 'Actions', value: 'actions', width: "90px", sortable: false }
		],
		header_updatelog: [
			{ text: 'Date', value: 'date' },
			{ text: 'Type', value: 'type' },
			{ text: 'Name', value: 'name' },
			{ text: 'Old Version', value: 'old_version' },
			{ text: 'New Version', value: 'new_version' },
			{ text: 'Status', value: 'status' }
		],
		 header_users: [
			{ text: 'Login', value: 'user_login' },
			{ text: 'Display Name', value: 'display_name' },
			{ text: 'Email', value: 'user_email' },
			{ text: 'Role(s)', value: 'roles' },
			{ text: 'Actions', value: 'actions', sortable: false }
		],
		applied_site_filter: [],
		applied_site_filter_logic: [],
		applied_site_filter_version: [],
		applied_site_filter_status: [],
		select_bulk_action: null,
		bulk_actions: [
			{ header: "Script" },
			{ name: "Migrate", value: "migrate", arguments: [
				{ name: "Url", value: "url" },
				{ name: "Skip url override", value: "skip-url-override" }
			]},
			{ name: "Apply SSL", value: "applyssl"  },
			{ name: "Apply SSL with www", value: "applysslwithwww" },
			{ name: "Launch", value: "launch" },
			{ header: "Command" },
			{ name: "Backup", value: "backup" },
			{ name: "SSH", value: "ssh", arguments: [
				{ name: "Commands", value: "command" },
				{ name: "Script", value: "script" }
			]},
			{ name: "Sync", value: "sync" },
			{ name: "Activate", value: "activate" },
			{ name: "Deactivate", value: "deactivate" },
			{ name: "Snapshot", value: "snapshot" },
			{ name: "Remove", value: "remove" }
		 ],
		 select_bulk_action_arguments: null,
		 snackbar: { show: false, message: "" }
	},
	watch: {
		route() {
			this.triggerRoute()
		},
		route_path() {
			this.triggerPath()
		},
		runningJobs() {
			this.view_console.show = true
		},
    },
	filters: {
		safeUrl: function( url ) {
			return url.replace('#', '%23' )
		},
		timeago: function( timestamp ){
			return moment.utc( timestamp, "YYYY-MM-DD hh:mm:ss").fromNow();
		},
		formatTime: function ( value ) {
			var sec_num = parseInt(value, 10); // don't forget the second param
			var hours   = Math.floor(sec_num / 3600);
			var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
			var seconds = sec_num - (hours * 3600) - (minutes * 60);

			if (hours   < 10) {hours   = "0"+hours;}
			if (minutes < 10) {minutes = "0"+minutes;}
			if (seconds < 10) {seconds = "0"+seconds;}
			return minutes + ':' + seconds;
		},
		formatProvider: function (value) {
			if (value == 'wpengine') {
				return "WP Engine"
			}
			if (value == 'kinsta') {
				return "Kinsta"
			}
		},
		formatSize: function (fileSizeInBytes) {
    var i = -1;
    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
    do {
        fileSizeInBytes = fileSizeInBytes / 1024;
        i++;
    } while (fileSizeInBytes > 1024);

    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
		},
		formatGBs: function (fileSizeInBytes) {
			fileSizeInBytes = fileSizeInBytes / 1024 / 1024 / 1024;
			return Math.max(fileSizeInBytes, 0.1).toFixed(2);
		},
		formatLargeNumbers: function (number) {
			if ( isNaN(number) || number == null ) {
				return null;
			} else {
				return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
			}
		},
		formatk: function (num) {
			if (num < 9999 ) {
				return numeral(num).format('0,0');
			}
			if (num < 99999 ) {
				return numeral(num).format('0.0a');
			}
			if (num < 999999 ) {
				return numeral(num).format('0a');
			}
			return numeral(num).format('0.00a');
		},
		formatPercentage: function (percentage) {
			return Math.max(percentage, 0.1).toFixed(0);
		},
		formatPercentageFixed: function (percentage) {
			return (Math.max(percentage, 0.1) * 100 ).toFixed(2) + '%';
		},
		pretty_timestamp: function (date) {
			// takes in '2018-06-18 19:44:47' then returns "Monday, Jun 18, 2018, 7:44 PM"
			formatted_date = new Date(date).toLocaleTimeString("en-us", pretty_timestamp_options);
			return formatted_date;
		},
		pretty_timestamp_epoch: function (date) {
			// takes in '1577584719' then returns "Monday, Jun 18, 2018, 7:44 PM"
			d = new Date(0);
			d.setUTCSeconds(date);
			formatted_date = d.toLocaleTimeString("en-us", pretty_timestamp_options);
			return formatted_date;
		}
	},
	mounted() {
		window.addEventListener('popstate', () => {
			this.updateRoute( window.location.pathname )
		})
		if ( typeof wpApiSettings == "undefined" ) {
			window.history.pushState( {}, 'login', "/account/login" )
			this.route = "login"
			return;
		} else {
			this.wp_nonce = wpApiSettings.nonce
		}
		if ( this.socket == "/ws" ) {
			console.log("Socket not defined")
			window.history.pushState( {}, 'connect', "/account/connect" )
			this.route = "connect"
			return;
		}
		this.checkRequestedSites()
		this.fetchAccounts()
		this.fetchRecipes()
		if ( this.role == 'administrator' ) {
			this.fetchProcesses();
		}
		this.updateRoute( window.location.pathname )

		if ( this.route == "" ) {
			this.triggerRoute()
		}
	},
	computed: {
		gravatar() {
			return 'https://www.gravatar.com/avatar/' + md5( this.current_user_email.trim().toLowerCase() ) + '?s=80&d=mp'
		},
		fetchInvite() {
			var urlParams = new URLSearchParams( this.querystring )
			var invite = { account: urlParams.get('account'), token: urlParams.get('token') }
			return invite
		},
		selected_default_recipes() {
			if ( typeof this.dialog_account.records.account.defaults.recipes == 'undefined' ) {
				return "";
			} else {
				return this.dialog_account.records.account.defaults.recipes;
			}
		},
		dialogCapturesPagesText() {
			if ( typeof this.dialog_captures.capture.pages == 'undefined' ) {
				return ""
			}
			if ( this.dialog_captures.capture.pages.length == 1 ) {
				return "Page"
			} else {
				return "Pages"
			}
		},
		filterSitesWithErrors() {
			return this.sites.filter( s => s.console_errors != "" )
		},
		filterCount() {
			return this.applied_site_filter.length + this.applied_site_filter_version.length + this.applied_site_filter_status.length
		},
		runningJobs() {
			return this.jobs.filter(job => job.status != 'done' && job.status != 'error' ).length;
		},
		completedJobs() {
			return this.jobs.filter(job => job.status == 'done' || job.status == 'error' ).length;
		},
		filteredRecipes() {
			return this.recipes.filter( recipe => recipe.user_id != 'system' );
		},
		dnsRecords() {
			count = 0;
			this.dialog_domain.records.forEach( r => {
				if ( r.update.record_status == 'new-record' ) {
					return
				}
				if ( typeof r.value === 'string' ) {
					count = count + 1;
				}
				if ( typeof r.value === 'object' ) {
					count = count + r.value.length
				}
			})
			return count;
		},
		allDomains() {
			return Object.keys( this.domains ).length;
		},
		plan_usage_pricing_sites() {
			extra_sites = parseInt( this.dialog_account.records.account.plan.usage.sites ) - parseInt( this.dialog_account.records.account.plan.limits.sites )
			if ( extra_sites > 0 ) {
				unit_price = this.configurations.usage_pricing.sites.cost
				if ( this.configurations.usage_pricing.sites.interval != this.dialog_account.records.account.plan.interval ) {
					unit_price = this.configurations.usage_pricing.sites.cost / this.configurations.usage_pricing.sites.interval
					unit_price = unit_price * this.dialog_account.records.account.plan.interval
		}
			}
			return unit_price
	},
		plan_usage_pricing_storage() {
			extra_storage = ( parseInt( this.dialog_account.records.account.plan.usage.storage ) / 1024 / 1024 / 1024 ) - parseInt( this.dialog_account.records.account.plan.limits.storage ) 
			if ( extra_storage > 0 ) {
				unit_price = this.configurations.usage_pricing.storage.cost
				if ( this.configurations.usage_pricing.storage.interval != this.dialog_account.records.account.plan.interval ) {
					unit_price = this.configurations.usage_pricing.storage.cost / this.configurations.usage_pricing.storage.interval
					unit_price = unit_price * this.dialog_account.records.account.plan.interval
				}
			}
			return unit_price
		},
		plan_usage_pricing_visits() {
			extra_visits = Math.round ( ( parseInt( this.dialog_account.records.account.plan.usage.visits ) - parseInt( this.dialog_account.records.account.plan.limits.visits ) ) / parseInt ( this.configurations.usage_pricing.traffic.quantity ) )
			if ( extra_visits > 0 ) {
				unit_price = this.configurations.usage_pricing.traffic.cost
				if ( this.configurations.usage_pricing.traffic.interval != this.dialog_account.records.account.plan.interval ) {
					unit_price = this.configurations.usage_pricing.traffic.cost / this.configurations.usage_pricing.traffic.interval
					unit_price = unit_price * this.dialog_account.records.account.plan.interval
				}
			}
			return parseInt( unit_price )
		},
		plan_usage_estimate() {
			if ( typeof this.dialog_account.records.account.plan == 'object' ) {
				extras = 0
				addons = 0
				this.dialog_account.records.account.plan.addons.forEach( addon => {
					if ( addon.price != "" ) {
						addons = addons + (  parseInt( addon.quantity ) * parseInt( addon.price ) )
					}
				})
				total = parseInt( addons ) + parseInt( this.dialog_account.records.account.plan.price )
				units = [] 
				units[1] = "month"
				units[4] = "quarter"
				units[6] = "biannually"
				units[12] = "year"
				unit = units[ this.dialog_account.records.account.plan.interval ]
				extra_sites = parseInt( this.dialog_account.records.account.plan.usage.sites ) - parseInt( this.dialog_account.records.account.plan.limits.sites )
				extra_storage = Math.round ( ( ( parseInt( this.dialog_account.records.account.plan.usage.storage ) / 1024 / 1024 / 1024 ) - parseInt( this.dialog_account.records.account.plan.limits.storage ) ) / 10 )
				extra_visits = Math.round ( ( parseInt( this.dialog_account.records.account.plan.usage.visits ) - parseInt( this.dialog_account.records.account.plan.limits.visits ) ) / parseInt ( this.configurations.usage_pricing.traffic.quantity ) )
				if ( extra_sites > 0 ) {
					unit_price = this.configurations.usage_pricing.sites.cost
					if ( this.configurations.usage_pricing.sites.interval != this.dialog_account.records.account.plan.interval ) {
						unit_price = this.configurations.usage_pricing.sites.cost / this.configurations.usage_pricing.sites.interval
						unit_price = unit_price * this.dialog_account.records.account.plan.interval
					}
					extras = extras + ( extra_sites * unit_price )
				}
				if ( extra_storage > 0 ) {
					unit_price = this.configurations.usage_pricing.storage.cost
					if ( this.configurations.usage_pricing.storage.interval != this.dialog_account.records.account.plan.interval ) {
						unit_price = this.configurations.usage_pricing.storage.cost / this.configurations.usage_pricing.storage.interval
						unit_price = unit_price * this.dialog_account.records.account.plan.interval
					}
					extras = extras + ( extra_storage * unit_price )
				}
				if ( extra_visits > 0 ) {
					unit_price = this.configurations.usage_pricing.traffic.cost
					if ( this.configurations.usage_pricing.traffic.interval != this.dialog_account.records.account.plan.interval ) {
						unit_price = this.configurations.usage_pricing.traffic.cost / this.configurations.usage_pricing.traffic.interval
						unit_price = unit_price * this.dialog_account.records.account.plan.interval
					}
					extras = extras + ( extra_visits * unit_price )
				}
				total = parseInt( addons ) + parseInt( extras ) + parseInt( this.dialog_account.records.account.plan.price )
				return `$${total} <small>per ${unit}</small>`
			}
			return ""

		}
	},
	methods: {
		updateRoute( href ) {
			// Remove trailing slash
			if ( href.slice(-1) == "/" ) {
				href = href.slice(0, -1)
			}
			// Catch all nested routes to their parent route.
			if ( href.match(/\//g).length > 1 ) {
				this.route_path = href.split('/').slice( 3 ).join( "/" )
				href = href.split('/').slice( 0, 3 ).join( "/" )
			} else {
				this.route_path = ""
			}
			this.route = this.routes[ href ]
		},
		triggerRoute() {
			if ( this.wp_nonce == "" ) {
				window.history.pushState( {}, 'login', "/account/login" )
				this.route = "login"
				this.loading_page = false;
				return;
			}
			if ( this.route == "login" ) {
				this.selected_nav = ""
				this.loading_page = false;
			}
			if ( this.route == "dns" ) {
				if ( this.allDomains == 0 ) {
					this.loading_page = true;
				}
				this.selected_nav = 1
				this.fetchDomains()
			}
			if ( this.route == "users" ) {
				this.selected_nav = 6
				this.fetchAllUsers();
			}
			if ( this.route == "cookbook" ) {
				this.selected_nav = 3
				this.loading_page = false;
			}
			if ( this.route == "handbook" ) {
				this.selected_nav = 4
				this.loading_page = false;
			}
			if ( this.route == "keys" ) {
				this.selected_nav = ""
				this.loading_page = false;
				this.fetchKeys()
			}
			if ( this.route == "defaults" ) {
				this.selected_nav = ""
				this.loading_page = false;
				this.fetchDefaults()
			}
			if ( this.route == "profile" ) {
				this.selected_nav = ""
				this.loading_page = false;
			}
			if ( this.route == "accounts" ) {
				this.selected_nav = 5
				this.loading_page = false;
			}
			if ( this.route == "billing" ) {
				this.fetchBilling()
				this.selected_nav = 7
				this.loading_page = false;
			}
			if ( this.route == "configurations" ) {
				this.fetchConfigurations()
				this.loading_page = false;
			}
			if ( this.route == "sites" ) {
				if ( this.sites.length == 0 ) {
					this.loading_page = true;
				}
				this.selected_nav = 0
				this.fetchSites()
			}
			if ( this.route == "health" ) {
				if ( this.sites.length == 0 ) {
					this.loading_page = true;
				}
				this.selected_nav = 2
				this.fetchSites()
			}
			if ( this.fetchInvite.account ) {
				this.fetchInviteInfo()
				this.route = "invite"
				this.loading_page = false;
				return
			}
			if ( this.route == "" ) {
				if ( this.sites.length == 0 ) {
					this.loading_page = true;
				}
				this.route = "sites"
				this.selected_nav = 0
			}
		},
		triggerPath() {
			if ( this.route_path == "" ) {
				this.dialog_domain.step = 1
				this.dialog_site.step = 1
				this.dialog_account.step = 1
			}
			if ( this.route == "dns" && this.route_path != "" ) {
				this.dialog_domain.step = 2				
				domain = this.domains.filter( d => d.domain_id == this.route_path )[0]
				if ( domain ) {
					this.modifyDNS( domain )
				}
			}
			if ( this.route == "sites" && this.route_path == "new" ) {
				this.dialog_site.step = 3
				return
			}
			if ( this.route == "sites" && this.route_path != "" ) {
				this.dialog_site.step = 2				
				site = this.sites.filter( s => s.site_id == this.route_path )[0]
				if ( site ) {
					this.showSite( site )
				}
			}
			if ( this.route == "accounts" && this.route_path != "" ) {
				this.dialog_account.step = 2				
				account = this.accounts.filter( a => a.account_id == this.route_path )[0]
				if ( account ) {
					this.showAccount( account.account_id )
				}
			}
		},
		goToPath ( href ) {
			this.updateRoute( href )
			window.history.pushState( {}, this.routes[href], href )
		},
		resetPassword() {
			this.login.loading = true
			if ( ! this.$refs.reset.validate() ) {
				this.login.loading = false
				return
			}
			axios.post( '/wp-json/captaincore/v1/login/', {
					'command': "reset",
					'login': this.login
				})
				.then( response => {
					this.login.message = "A password reset email is on it's way."
					this.login.loading = false
				})
				.catch(error => {
					console.log(error);
				});
		},
		signIn() {
			this.login.loading = true
			if ( ! this.$refs.login.validate() ) {
				this.login.loading = false
				return
			}
			axios.post( '/wp-json/captaincore/v1/login/', {
					'command': "signIn",
					'login': this.login
				})
				.then( response => {
					if ( typeof response.data.errors === 'undefined' ) {
						window.location = "/account"
						return
					}
					this.login.errors = response.data.errors
					this.login.loading = false
				})
				.catch(error => {
					console.log(error);
				});
		},
		signOut() {
			axios.post( '/wp-json/captaincore/v1/login/', {
				command: "signOut" 
			})
			.then( response => {
				window.location = "/account/login"
				this.route = "login"
				this.wp_nonce = "";
			})
		},
		copyText( value ) {
			var clipboard = document.getElementById("clipboard");
			clipboard.value = value;
			clipboard.focus()
			clipboard.select()
			document.execCommand("copy");
			this.snackbar.message = "Copied to clipboard.";
			this.snackbar.show = true;
		},
		copyInviteLink( account, token ) {
			link = window.location.origin + window.location.pathname + `?account=${account}&token=${token}`
			this.copyText( link )
		},
		copySFTP( key ) {
			sftp_info = `Address: ${key.address}\nUsername: ${key.username}\nPassword: ${key.password}\nProtocol: ${key.protocol}\nPort: ${key.port}`
			this.copyText( sftp_info );
		},
		copyDatabase( key ) {
			database_info = `Database: ${key.database}\nDatabase Username: ${key.database_username}\nDatabase Password: ${key.database_password}`
			this.copyText( database_info );
		},
		triggerEnvironmentUpdate(){
			if ( this.dialog_site.site.tabs == "tab-Site-Management" && this.dialog_site.site.tabs_management == "tab-Stats" ) {
				this.fetchStats()
			}
			if ( this.dialog_site.site.tabs == "tab-Site-Management" && this.dialog_site.site.tabs_management == "tab-Backups" && this.dialog_site.backup_step == 2 ) {
				this.viewBackups()
			}
			if ( this.dialog_site.site.tabs == "tab-Site-Management" && this.dialog_site.site.tabs_management == "tab-Backups" && this.dialog_site.backup_step == 3 ) {
				this.viewQuicksaves()
			}
			if ( this.dialog_site.site.tabs == "tab-Site-Management" && this.dialog_site.site.tabs_management == "tab-Backups" && this.dialog_site.backup_step == 4 ) {
				this.viewSnapshots()
			}
		},
		clearFilters() {
			this.applied_site_filter = []
			this.applied_site_filter_version = []
			this.applied_site_filter_status = []
		},
		removeFilter (item) {
			const index = this.applied_site_filter.indexOf(item.name)
			if (index >= 0) { 
				this.applied_site_filter.splice(index, 1);
				this.filterSites();
			}
		},
		user_name( user_id ) {
			users = this.users.filter( u => u.user_id == user_id )
			if ( users.length != 1 ) {
				return ""
			}
			return users[0].name
		},
		account_name( account_id ) {
			accounts = this.accounts.filter( a => a.account_id == account_id )
			if ( accounts.length != 1 ) {
				return ""
			}
			return accounts[0].name
		},
		compare(key, order='asc') {
			return function(a, b) {
				//if(!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
				//	// property doesn't exist on either object
				//	return 0;
				//}
				if ( key == 'name' ) {
					varA = a.name || "";
					varB = b.name || "";
				}
				if ( key == 'multisite' ) {
					varA = parseInt(a.subsite_count) || 0;
					varB = parseInt(b.subsite_count) || 0;
				}
				if ( key == 'visits' ) {
					varA = parseInt(a[key].replace(/\,/g,'')) || 0;
					varB = parseInt(b[key].replace(/\,/g,'')) || 0;
				}
				if ( key == 'storage' ) {
					varA = parseInt(a.storage) || 0;
					varB = parseInt(b.storage) || 0;
				}
				if ( key == 'provider' ) {
					varA = a.provider || "";
					varB = b.provider || "";
				}
				let comparison = 0;
				if (varA > varB) {
					comparison = 1;
				} else if (varA < varB) {
					comparison = -1;
				}
				return (
					(order == 'desc') ? (comparison * -1) : comparison
				);
			};
		},
		resetColors() {
			this.$vuetify.theme.themes.light = {
				primary: '#1976D2',
				secondary: '#424242',
				accent: '#82B1FF',
				error: '#FF5252',
				info: '#2196F3',
				success: '#4CAF50',
				warning: '#FFC107'
			}
		},
		sortTree( data ) {
			if ( ! data ) { return }
            data.sort( (a, b) => a.type > b.type || a.name > b.name )
            for ( var i = 0; i< data.length; i++ ) {
                var val = data[i]
                if ( val.children ) { this.sortTree( val.children ) }
            }
		},
		saveGlobalConfigurations() {
			this.dialog_configure_defaults.loading = true;
			this.configurations.colors = this.$vuetify.theme.themes.light
			// Prep AJAX request
			var data = {
				'action': 'captaincore_local',
				'command': "saveGlobalConfigurations",
				'value': this.configurations
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.snackbar.message = response.data
					this.snackbar.show = true
				})
				.catch(error => {
					this.snackbar.message = error.response
					this.snackbar.show = true
			});
		},
		saveGlobalDefaults() {
			this.dialog_configure_defaults.loading = true;
			// Prep AJAX request
			var data = {
				'action': 'captaincore_local',
				'command': "saveGlobalDefaults",
				'value': this.defaults
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.snackbar.message = response.data
					this.snackbar.show = true
				})
				.catch(error => {
					this.snackbar.message = error.response
					this.snackbar.show = true
			});
		},
		saveDefaults() {
			this.dialog_configure_defaults.loading = true;
			// Prep AJAX request
			var data = {
				'action': 'captaincore_local',
				'command': "saveDefaults",
				'value': this.dialog_account.records.account
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					console.log( response.data )
					this.dialog_configure_defaults.show = false;
					this.dialog_configure_defaults.loading = false;
				})
				.catch(error => {
					console.log(error.response)
			});
		},
		viewJob( job_id ) {
			this.dialog_job.task = this.jobs.filter( j => j.job_id == job_id )[0];
			this.console = 4
		},
		toggleConsole( index ) {
			if ( this.console != index ) {
				this.footer_height = "202px"
				this.view_console.open = true
				this.console = index
				return
			}
			if ( this.footer_height == "28px" ) {
				this.footer_height = "202px"
				this.view_console.open = true
				this.console = index
			} else {
				this.footer_height = "28px"
				this.view_console.open = false
				this.console = 0
			}
		},
		openConsole( index ) {
			this.console = index
			this.view_console.open = true
			this.footer_height = "202px"
		},
		closeConsole() {
			this.view_console.open = false
			this.footer_height = "28px"
		},
		sortSites( key ) {
			if ( this.toggle_site_counter.key == key ) {
				this.toggle_site_counter.count++;
				this.sort_direction = "asc";
			} else {
				this.toggle_site_counter.key = key;
				this.toggle_site_counter.count = 1;
				this.sort_direction = "desc";
			}
			// Reset sort to default on 3rd click
			if ( this.toggle_site_counter.count == 3 ) {
				this.sites = this.sites.sort( this.compare( "name", this.sort_direction ) );
				this.toggle_site_counter = { key: "", count: 0 };
				this.toggle_site_sort = null;
				this.sort_direction = "desc";
				return
			}
			// Order these
			this.sites = this.sites.sort( this.compare( key, this.sort_direction ) );
		},
		removeFromBulk( site_id ) {
			this.sites_selected = this.sites_selected.filter(site => site.site_id != site_id);
		},
		loginSite(site_id, username) {

			// Adds new job
			job_id = Math.round((new Date()).getTime());
			description = "Login as '" + username + "' to " + site.name;
			this.jobs.push({"job_id": job_id,"description": description, "status": "running", "command":"login"});

			// Prep AJAX request
			var data = {
				'action': 'captaincore_ajax',
				'post_id': site_id,
				'command': "fetch-one-time-login",
				'value': username,
				'environment': this.dialog_site.environment_selected.environment
			}

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					if ( response.data.includes("http") ) {
					window.open( response.data );
						this.jobs.filter(job => job.job_id == job_id)[0].status = "done";
					} else {
						this.jobs.filter(job => job.job_id == job_id)[0].status = "error";
						this.snackbar.message = description + " failed.";
						this.snackbar.show = true;
					}
					
				})
				.catch(error => {
					this.jobs.filter(job => job.job_id == job_id)[0].status = "error";
					this.snackbar.message = description + " failed.";
					this.snackbar.show = true;
					console.log(error.response)
			});
		},
		inputFile (newFile, oldFile) {

			if (newFile && oldFile) {
				// Uploaded successfully
				if (newFile.success && !oldFile.success) {
					new_response = JSON.parse( newFile.response );
					if ( new_response.response == "Error" ) {

						if ( this.new_theme.show ) {
							this.new_theme.show = false;
							this.snackbar.message = "Installing theme failed.";
							this.snackbar.show = true;
							description = "Installing theme '" + newFile.name + "' to " + this.new_theme.site_name;

							// Adds new job
							job_id = Math.round((new Date()).getTime());
							this.jobs.push({"job_id": job_id,"description": description, "status": "error", stream: []});
						}

						if ( this.new_plugin.show ) {
							this.new_plugin.show = false;
							this.snackbar.message = "Installing plugin failed.";
							this.snackbar.show = true;
							description = "Installing plugin '" + newFile.name + "' to " + this.new_plugin.site_name;
							
							// Adds new job
							job_id = Math.round((new Date()).getTime());
							this.jobs.push({"job_id": job_id,"description": description, "status": "error", stream: []});
						}

					}
					if ( new_response.response == "Success" && new_response.url ) {

						if ( this.new_plugin.show ) {
							this.new_plugin.show = false;

							this.upload = [];

							// run wp cli with new plugin url and site
							site_ids = this.new_plugin.sites.map( s => s.site_id );

							// Adds new job
							job_id = Math.round((new Date()).getTime());
							description = "Installing plugin '" + newFile.name + "' to " + this.new_plugin.site_name;
							this.jobs.push({"job_id": job_id, "site_id": site_ids, "description": description, "status": "queued", stream: [], "command": "manage"});

							// Builds WP-CLI
							wp_cli = "wp plugin install '" + new_response.url + "' --force --activate"

							// Prep AJAX request
							var data = {
								'action': 'captaincore_install',
								'post_id': site_ids,
								'command': "manage",
								'value': "ssh",
								'background': true,
								'environment': this.new_plugin.environment_selected,
								'arguments': { "name":"Commands","value":"command","command":"ssh","input": wp_cli }
							};

							// Housecleaning
							this.new_plugin.sites = [];
							this.new_plugin.site_name = "";
							this.new_plugin.environment_selected = "";
						}
						if ( this.new_theme.show ) {
							this.new_theme.show = false;
							this.upload = [];

							// run wp cli with new plugin url and site
							site_ids = this.new_theme.sites.map( s => s.site_id );

							// Adds new job
							job_id = Math.round((new Date()).getTime());
							description = "Installing theme '" + newFile.name + "' to " + this.new_theme.site_name;
							this.jobs.push({"job_id": job_id, "site_id": site_ids, "description": description, "status": "queued", stream: [], "command": "manage"});

							// Builds WP-CLI
							wp_cli = "wp theme install '" + new_response.url + "' --force"

							// Prep AJAX request
							var data = {
								'action': 'captaincore_install',
								'post_id': site_ids,
								'command': "manage",
								'value': "ssh",
								'background': true,
								'environment': this.new_theme.environment_selected,
								'arguments': { "name":"Commands","value":"command","command":"ssh","input": wp_cli }
							};

							// Housecleaning
							this.new_theme.sites = [];
							this.new_theme.site_name = "";
							this.new_theme.environment_selected = "";
						}

						self = this;
						axios.post( ajaxurl, Qs.stringify( data ) )
							.then( response => {
								self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
								self.runCommand( response.data );
							})
							.catch(error => {
								console.log( error.response )
							});
					}

				}

			}

			// Automatically activate upload
			if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
				if (!this.$refs.upload.active) {
					this.$refs.upload.active = true;
				}
			}
		},
		new_site_preload_staging() {
			// Copy production address to staging field
			this.dialog_new_site.environments[1].address = this.dialog_new_site.environments[0].address;
			if ( this.dialog_new_site.environments[0].address.includes(".kinsta.cloud") ) {
				this.dialog_new_site.environments[1].address = "staging-" + this.dialog_new_site.environments[0].address
			}

			if ( this.dialog_new_site.provider == "kinsta" ) {
				// Copy production username to staging field
				this.dialog_new_site.environments[1].username = this.dialog_new_site.environments[0].username;
				// Copy production password to staging field (If Kinsta address)
				this.dialog_new_site.environments[1].password = this.dialog_new_site.environments[0].password;
			} else {
				// Copy production username to staging field with staging suffix
				this.dialog_new_site.environments[1].username = this.dialog_new_site.environments[0].username + "-staging";
			}

			// Copy production port to staging field
			this.dialog_new_site.environments[1].port = this.dialog_new_site.environments[0].port;
			// Copy production protocol to staging field
			this.dialog_new_site.environments[1].protocol = this.dialog_new_site.environments[0].protocol;
			// Copy production home directory to staging field
			this.dialog_new_site.environments[1].home_directory = this.dialog_new_site.environments[0].home_directory;
		},
		edit_site_preload_staging() {
			// Copy production address to staging field
			this.dialog_edit_site.site.environments[1].address = this.dialog_edit_site.site.environments[0].address;
			if ( this.dialog_edit_site.site.environments[0].address.includes(".kinsta.cloud") ) {
				this.dialog_edit_site.site.environments[1].address = "staging-" + this.dialog_edit_site.site.environments[0].address
			}

			if ( this.dialog_edit_site.site.provider == "kinsta" ) {
				// Copy production username to staging field
				this.dialog_edit_site.site.environments[1].username = this.dialog_edit_site.site.environments[0].username;
				// Copy production password to staging field (If Kinsta address)
				this.dialog_edit_site.site.environments[1].password = this.dialog_edit_site.site.environments[0].password;
			} else {
				// Copy production username to staging field with staging suffix
				this.dialog_edit_site.site.environments[1].username = this.dialog_edit_site.site.environments[0].username + "-staging";
			}

			// Copy production port to staging field
			this.dialog_edit_site.site.environments[1].port = this.dialog_edit_site.site.environments[0].port;
			// Copy production protocol to staging field
			this.dialog_edit_site.site.environments[1].protocol = this.dialog_edit_site.site.environments[0].protocol;
			// Copy production home directory to staging field
			this.dialog_edit_site.site.environments[1].home_directory = this.dialog_edit_site.site.environments[0].home_directory;
		},
		checkRequestedSites() {
			var data = {
				'action': 'captaincore_user',
				'command': "fetchRequestedSites",
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
					if ( this.requested_sites.length != 0 ) {
						setTimeout(this.checkRequestedSites, 5000)
					}
					if ( this.requested_sites.length == 0 && this.role == 'administrator' ) {
						setTimeout(this.checkRequestedSites, 5000)
					}
				})
				.catch( error => console.log( error ) );
		},
		requestSite() {
			if ( this.dialog_request_site.request.name == "" || this.dialog_request_site.request.account_id == "" ) {
				this.snackbar.message = "Please enter a site name."
				this.snackbar.show = true
				return
			}
			this.dialog_request_site.request.created_at = Math.round((new Date()).getTime() / 1000)
			this.dialog_request_site.request.step = 1
			var data = {
				'action': 'captaincore_account',
				'command': "requestSite",
				'value': this.dialog_request_site.request,
				'account_id': this.dialog_request_site.request.account_id
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
					if ( this.requested_sites.length == 1 ) {
						this.checkRequestedSites()
					}
				})
				.catch( error => console.log( error ) );
			name = this.dialog_request_site.request.name
			this.snackbar.message = `Requesting new site for ${name}`
			this.snackbar.show = true
			this.dialog_request_site = { show: false, request: { name: "", account_id: "", notes: "" } }
		},
		backRequestSite( site_request ) {
			var data = {
				'action': 'captaincore_account',
				'command': "backRequestSite",
				'value': site_request,
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
				})
				.catch( error => console.log( error ) )
		},
		continueRequestSite( site_request ) {
			var data = {
				'action': 'captaincore_account',
				'command': "continueRequestSite",
				'value': site_request,
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
				})
				.catch( error => console.log( error ) )
		},
		updateRequestSite() {
			var data = {
				'action': 'captaincore_account',
				'command': "updateRequestSite",
				'value': this.dialog_site_request.request,
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_site_request.show = false
					this.requested_sites = response.data
				})
				.catch( error => console.log( error ) )
		},
		modifyRequest( index ) {
			this.dialog_site_request.show = true
			this.dialog_site_request.request = JSON.parse ( JSON.stringify ( this.requested_sites[index] ) )
		},
		finishRequest( index ) {
			site_request = this.requested_sites[index]
			var data = {
				'action': 'captaincore_account',
				'command': "deleteRequestSite",
				'value': site_request,
				'account_id': site_request.account_id
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
				})
				.catch( error => console.log( error ) )
		},
		cancelRequest( index ) {
			site_request = this.requested_sites[index]
			should_proceed = confirm( `Cancel request to create site "${site_request.name}" for account "${this.account_name( site_request.account_id )}".` )
			if ( ! should_proceed ) {
				return
			}
			var data = {
				'action': 'captaincore_account',
				'command': "deleteRequestSite",
				'value': site_request,
				'account_id': site_request.account_id
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.requested_sites = response.data
				})
				.catch( error => console.log( error ) )
		},
		submitNewSite() {
			this.dialog_new_site.saving = true
			new_site = this.dialog_new_site
			new_site.shared_with = new_site.shared_with.map( a => a.account_id )
			var data = {
				'action': 'captaincore_ajax',
				'command': "newSite",
				'value': this.dialog_new_site
			};
			site_name = this.dialog_new_site.domain;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Read JSON response
					var response = response.data;

					// If error then response
					if ( response.errors.length > 0 ) {
						this.dialog_new_site.saving = false
						this.dialog_new_site.errors = response.errors
						return;
					}

					if ( response.response = "Successfully added new site" ) {
						this.fetchSiteInfo( response.site_id )
						// Fetch updated accounts
						axios.get(
							'/wp-json/captaincore/v1/accounts', {
								headers: {'X-WP-Nonce':this.wp_nonce}
							})
							.then( response => {
								this.accounts = response.data
							});
						
						// Start job
						description = "Adding " + site_name;
						job_id = Math.round((new Date()).getTime());
						this.jobs.push({"job_id": job_id,"description": description, "status": "running", stream: []});

						// Run prep immediately after site added.
						var data = {
							'action': 'captaincore_install',
							'command': "update",
							'post_id': response.site_id
						};
						axios.post( ajaxurl, Qs.stringify( data ) )
							.then( r => {
								this.jobs.filter(job => job.job_id == job_id)[0].job_id = r.data
								this.runCommand( r.data )
							})
					}
				});
		},
		updateSite() {
			this.dialog_edit_site.loading = true;
			site_update = JSON.parse ( JSON.stringify ( this.dialog_edit_site.site ) )
			site_update.shared_with = site_update.shared_with.map( a => a.account_id )
			site_name = site_update.name
			var data = {
				'action': 'captaincore_ajax',
				'command': "updateSite",
				'value': site_update
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					var response = response.data

					// If error then response
					if ( response.response.includes("Error:") ) {
						this.dialog_edit_site.errors = [ response.response ];
						console.log( response.response );
						return;
					}

					if ( response.response = "Successfully updated site" ) {
						this.dialog_site.step = 2
						this.dialog_edit_site = { show: false, loading: false, site: {} }

						// Start job
						description = "Updating " + site_name;
						job_id = Math.round((new Date()).getTime());
						this.jobs.push({"job_id": job_id,"description": description, "status": "running", stream: []});

						// Run prep immediately after site added.
						var data = {
							'action': 'captaincore_install',
							'command': "update",
							'post_id': response.site_id
						};
						axios.post( ajaxurl, Qs.stringify( data ) )
							.then( r => {
								this.jobs.filter(job => job.job_id == job_id)[0].job_id = r.data
								this.runCommand( r.data )
							});
					}
				});
		},
		syncSite() {

			site = this.dialog_site.site

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'sync-data',
				environment: site.environment_selected
			};

			description = "Syncing " + site.name + " info";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({ "job_id": job_id, "description": description, "status": "queued", stream: [], "command": "syncSite", "site_id": site.site_id });

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with responsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
				})
				.catch( error => console.log( error ) );

		},
		bulkSyncSites() {

			should_proceed = confirm("Sync " + this.sites_selected.length + " sites for " + this.dialog_bulk.environment_selected.toLowerCase() + " environments info?");

			if ( ! should_proceed ) {
				return;
			}

			site_ids = this.sites_selected.map( site => site.site_id );
			site_names = this.sites_selected.length + " sites";

			var data = {
				action: 'captaincore_install',
				post_id: site_ids,
				command: 'sync-data',
				environment: this.dialog_bulk.environment_selected
			};

			self = this;
			description = "Syncing " + site_names + " site info";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({ "job_id": job_id, "description": description, "status": "queued", stream: [], "command": "syncSite", "site_id": site_ids });

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
				})
				.catch( error => console.log( error ) );

		},
		fetchSiteEnvironments( site_id ) {
			var data = {
				'action': 'captaincore_ajax',
				'command': "fetch-site-environments",
				'post_id': site_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_site.site.environments = response.data
					this.dialog_site.site.environments.forEach( e => {
						e.environment_label = e.environment + " Environment"
					})
					this.dialog_site.environment_selected = this.dialog_site.site.environments[0]
					this.dialog_site.loading = false
					if ( this.dialog_site.site.tabs_management == "tab-Users" ) {
						this.fetchUsers()
					}
					if ( this.dialog_site.site.tabs_management == "tab-Stats" ) {
						this.fetchStats()
					}
					if ( this.dialog_site.site.tabs_management == "tab-Updates" ) {
						this.fetchUpdateLogs( this.dialog_site.site.site_id )
					}
					if ( this.dialog_site.site.tabs_management == "tab-Backups" ) {
						this.viewQuicksaves()
						this.viewSnapshots()
					}
				});
		},
		fetchSiteDetails( site_id ) {
			var data = {
				'action': 'captaincore_ajax',
				'command': "fetch-site-details",
				'post_id': site_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					Object.keys( response.data.site ).forEach( key => {
						this.dialog_site.site[ key ] = response.data.site[ key ]
					})
					this.dialog_site.site.account = response.data.account
					this.dialog_site.site.shared_with = response.data.shared_with
				});
		},
		fetchSiteInfo( site_id ) {
			var data = {
				'action': 'captaincore_ajax',
				'command': "fetch-site",
				'post_id': site_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					response.data.forEach( site => {
						lookup = this.sites.filter(s => s.site_id == site.site_id).length;
						if (lookup == 1 ) {
							// Update existing site info
							site_update = this.sites.filter(s => s.site_id == site.site_id)[0];
							// Look through keys and update
							Object.keys(site).forEach(function(key) {
								// Skip updating environment_selected and tabs_management
								if ( key == "environment_selected" || key == "tabs" || key == "tabs_management" ) {
									return;
								}
							site_update[key] = site[key];
							})
							this.showSite( site_update )
						}
						if (lookup != 1 ) { 
							// Add new site info
							this.sites.push( site )
							this.showSite( site )
						}
					});
				});
		},
		fetchMissing() {
			if ( this.allDomains == 0 && this.modules.dns && this.domains_loading ) {
				this.fetchDomains()
			}
			if ( this.sites_loading ) {
				this.fetchSites()
			}
			if ( this.role == 'administrator' && this.users.length == 0 ) {
				this.fetchAllUsers()
			}
		},
		fetchDomains() {
			axios.get(
				'/wp-json/captaincore/v1/domains', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.domains = response.data
					this.domains_loading = false
					this.loading_page = false
					if ( this.dialog_domain.step == 2 && this.route_path != "" ) {
						domain = this.domains.filter( d => d.domain_id == this.route_path )[0]
						this.modifyDNS( domain )
					}
					setTimeout(this.fetchMissing, 4000)
				});
		},
		fetchAllUsers() {
			axios.get(
				'/wp-json/captaincore/v1/users', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.users = response.data
					this.loading_page = false
				});
		},
		fetchRecipes() {
			axios.get(
				'/wp-json/captaincore/v1/recipes', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.recipes = response.data;
				});
		},
		fetchProcesses() {
			axios.get(
				'/wp-json/captaincore/v1/processes', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.processes = response.data;
					setTimeout(this.fetchMissing, 1000)
				});
		},
		fetchKeys() {
			if ( this.role != 'administrator' ) {
				return
			}
			axios.get(
				'/wp-json/captaincore/v1/keys', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.keys = response.data;
					this.loading_page = false;
					setTimeout(this.fetchMissing, 4000)
				});
		},
		fetchDefaults() {
			if ( this.role != 'administrator' ) {
				return
			}
			axios.get(
				'/wp-json/captaincore/v1/defaults', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.defaults = response.data;
					this.loading_page = false;
					setTimeout(this.fetchMissing, 4000)
				});
		},
		fetchAccounts() {
			axios.get(
			'/wp-json/captaincore/v1/accounts', {
				headers: {'X-WP-Nonce':this.wp_nonce}
			})
			.then(response => {
				this.accounts = response.data
				if ( this.dialog_account.step == 2 && this.route_path != "" ) {
					this.showAccount( this.route_path )
				}
				setTimeout(this.fetchMissing, 1000)
			});
		},
		fetchConfigurations() {
			axios.get(
			'/wp-json/captaincore/v1/configurations', {
				headers: {'X-WP-Nonce':this.wp_nonce}
			})
			.then(response => {
				this.configurations = response.data
				this.configurations_loading = false
				setTimeout(this.fetchMissing, 1000)
			});
		},
		fetchFilterVersions( filters ) {
			filters = filters.map( f => f.name ).join(",")
			if ( filters != "" ) {
			axios.get(
			`/wp-json/captaincore/v1/filters/${filters}/versions`, {
				headers: {'X-WP-Nonce':this.wp_nonce}
			})
			.then(response => {
				this.site_filter_version = response.data
			})
			}
		},
		fetchFilterStatus( filters ) {
			filters = filters.map( f => f.name ).join(",")
			if ( filters != "" ) {
			axios.get(
			`/wp-json/captaincore/v1/filters/${filters}/statuses`, {
				headers: {'X-WP-Nonce':this.wp_nonce}
			})
			.then(response => {
				this.site_filter_status = response.data
			})
			}
		},
		fetchFilteredSites( site_filters ) {
			filters = site_filters.filters.map( f => f.name + "+" + f.type ).join(",")
			versions = site_filters.versions.map( v => v.name + '+' + v.slug + '+' + v.type ).join(',')
			statuses = site_filters.statuses.map( v => v.name + '+' + v.slug + '+' + v.type ).join(',')
			if ( filters != "" ) {
			axios.get(
			`/wp-json/captaincore/v1/filters/${filters}/sites/versions=${versions}/statuses=${statuses}`, {
				headers: {'X-WP-Nonce':this.wp_nonce}
			})
			.then(response => {
				sites_filtered = response.data
				this.sites.forEach( s => {
					if ( sites_filtered.includes( s.site ) ) {
						s.filtered = true
					} else {
						s.filtered = false
					}
				})
			})
			}
		},
		fetchSites() {
			this.sites_loading = false
			if ( this.role == 'administrator' && this.keys.length == 0 ) {
				axios.get(
				'/wp-json/captaincore/v1/keys', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.keys = response.data
				})
			}
			axios.get(
				'/wp-json/captaincore/v1/sites', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.sites = response.data
					this.loading_page = false
					if ( this.dialog_site.step == 2 && this.route_path != "" ) {
						site = this.sites.filter( d => d.site_id == this.route_path )[0]
						this.showSite( site )
					}
					setTimeout(this.fetchMissing, 1000)
			})
		},
		fetchStats() {

			environment = this.dialog_site.environment_selected
			environment.stats = "Loading";

			var data = {
				action: 'captaincore_ajax',
				post_id: this.dialog_site.site.site_id,
				command: 'fetchStats',
				environment: this.dialog_site.environment_selected.environment
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {

					if ( response.data.Error ) {
						environment.stats = response.data.Error 
						return;
					}

					chart_id = "chart_" + this.dialog_site.site.site_id + "_" + this.dialog_site.environment_selected.environment;
					chart_dom = document.getElementById( chart_id );		
					chart_dom.innerHTML = ""

					environment.stats = response.data
					
					bymonth={};
					environment.stats.stats.map( groupmonth );

					k = Object.keys( bymonth );
					names = Object.keys( bymonth ).map( k => bymonth[k].Name );
					pageviews = Object.keys( bymonth ).map( k => bymonth[k].Pageviews );
					visitors = Object.keys( bymonth ).map( k => bymonth[k].Visitors );
					
					// Generate chart
					environment.chart = new frappe.Chart( "#" + chart_id, {
						data: {
							labels: names,
							datasets: [
								{
									name: "Pageviews",
									values: pageviews,
								},
								{
									name: "Visitors",
									values: visitors,
								},
							],
						},
						type: "bar",
						height: 270,
						colors: ["light-blue", "#1564c0"],
						axisOptions: {
							xAxisMode: "tick",
							xIsSeries: 1
						},
						barOptions: {
							spaceRatio: 0.1,
							stacked: 1
						},
						showLegend: 0,
						
						});
					
				})
				.catch( error => console.log( error ) );

		},
		fetchUsers() {
			site = this.dialog_site.site
				var data = {
					'action': 'captaincore_ajax',
				'post_id': site.site_id,
					'command': "fetch-users",
			}
				axios.post( ajaxurl, Qs.stringify( data ) )
					.then( response => {
						response = response.data
						// Loop through environments and assign users
						Object.keys(response).forEach( key => {
							site.environments.filter( e => e.environment == key )[0].users = response[key];
							if ( response[key] == null ) {
								site.environments.filter( e => e.environment == key )[0].users = [];
							}
					})
				})
		},
		fetchUpdateLogs() {

			update_logs_count = this.dialog_site.site.update_logs.length;

			// Fetch updates if none exists
			if ( update_logs_count == 0 ) {

				var data = {
					'action': 'captaincore_ajax',
					'post_id': this.dialog_site.site.site_id,
					'command': "fetch-update-logs",
				};

				axios.post( ajaxurl, Qs.stringify( data ) )
					.then( response => {
						response = response.data
						// Loop through environments and assign users
						Object.keys(response).forEach( key => {
							this.dialog_site.site.environments.filter( e => e.environment == key )[0].update_logs = response[key];
							if ( response[key] == null ) {
								this.dialog_site.site.environments.filter( e => e.environment == key )[0].update_logs = [];
							}
						});
					});
			}
		},
		argumentsForActions() {
			arguments = [];
			this.select_bulk_action.forEach(action => {
				this.bulk_actions.filter(bulk_action => bulk_action.value == action).forEach(filtered_action => {
					if ( filtered_action.arguments ) {
						filtered_action.arguments.forEach(argument => arguments.push({ name: argument.name, value: argument.value, command: action }) );
					}
				});
			});
			this.select_bulk_action_arguments = arguments;
		},
		bulkEdit ( site_id, type ) {
			this.bulk_edit.show = true;
			site = this.dialog_site.site;
			this.bulk_edit.site_id = site_id;
			this.bulk_edit.site_name = site.name;
			this.bulk_edit.items = this.dialog_site.environment_selected[ type.toLowerCase() + "_selected" ];
			this.bulk_edit.type = type;
		},
		bulkEditExecute ( action ) {
			site_id = this.bulk_edit.site_id;
			site = this.dialog_site.site
			object_type = this.bulk_edit.type;
			object_singular = this.bulk_edit.type.slice(0, -1);
			items = this.bulk_edit.items.map(item => item.name).join(" ");
			if ( object_singular == "user" ) {
				items = this.bulk_edit.items.map(item => item.user_login).join(" ");
			}

			// Start job
			site_name = this.bulk_edit.site_name;
			description = "Bulk action '" + action + " " + this.bulk_edit.type + "' on " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id, "site_id": site_id, "description": description, "status": "queued", stream: [], "command": "manage"});

			// WP ClI command to send
			wpcli = "wp " + object_singular + " " + action + " " + items;

			// Set to loading.
			site.environments[0][ object_type ] = "Updating";
			if (site.environments[1] ) {
				site.environments[1][ object_type ] = "Updating";
			}

			this.bulk_edit.show = false;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
				});

		},
		fetchLink( site_id, snapshot_id ) {
			site = this.dialog_site.site
			snapshot = this.dialog_site.environment_selected.snapshots.filter( s => s.snapshot_id == snapshot_id )[0];

			var data = {
				'action': 'captaincore_ajax',
				'post_id': site_id,
				'command': 'fetchLink',
				'environment': site.environment_selected,
				'value': snapshot_id
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					snapshot.token = response.data.token;
					snapshot.expires_at = response.data.expires_at;
				})
				.catch( error => console.log( error ) );
		},
		promptBackupSnapshot( site_id ) {
			site = this.dialog_site.site
			this.dialog_backup_snapshot.show = true;
			this.dialog_backup_snapshot.site = site;
		},
		downloadBackupSnapshot( site_id ) {

			var post_id = this.dialog_backup_snapshot.site.site_id;
			var site_name = this.dialog_backup_snapshot.site.name;
			var environment = this.dialog_backup_snapshot.site.environment_selected;

			// Start job
			description = "Downloading snapshot for " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			var data = {
				'action': 'captaincore_install',
				'post_id': post_id,
				'command': 'snapshot',
				'environment': environment,
				'value': this.dialog_backup_snapshot.email,
				'notes': "User requested full snapshot"
			};

			if ( this.dialog_backup_snapshot.filter_toggle === false ) {
				data.filters = this.dialog_backup_snapshot.filter_options
				description = this.dialog_backup_snapshot.filter_options.join(", ").replace(/,([^,]*)$/,' and$1');
				data.notes = "User requested snapshot containing " + description;
			}
			
			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
					self.snackbar.message = "Generating snapshot for "+ self.dialog_backup_snapshot.site.name + ".";
					self.snackbar.show = true;
					self.dialog_backup_snapshot.site = {};
					self.dialog_backup_snapshot.show = false;
					self.dialog_backup_snapshot.email = self.dialog_backup_snapshot.current_user_email;
				})
				.catch( error => console.log( error ) );

		},
		copySite( site_id ) {
			site = this.dialog_site.site
			site_name = site.name;
			this.dialog_copy_site.show = true;
			this.dialog_copy_site.site = site;
			this.dialog_copy_site.options = this.sites.map(site => {
				option = { name: site.name, id: site.site_id };
				return option;
			}).filter(option => option.name != site_name );

			this.sites.map(site => site.name).filter(site => site != site_name );
		},
		editSite() {
			this.dialog_edit_site.site = JSON.parse ( JSON.stringify ( this.dialog_site.site ) )
			this.dialog_site.step = 4
		},
		deleteSite( site_id ) {
			site = this.dialog_site.site
			site_name = site.name;
			should_proceed = confirm("Delete site " + site_name + "?");

			if ( ! should_proceed ) {
				return;
			}

			// Start job
			description = "Deleting site " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued"});
			this.dialog_site.step = 1

			var data = {
				'action': 'captaincore_ajax',
				'command': 'deleteSite',
				'post_id': site.site_id
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].status = "done";
					// Remove item
					this.sites = this.sites.filter( site => site.site_id != site_id )
					this.snackbar.message = "Deleting site "+ site_name + ".";
				})
				.catch( error => console.log( error ) );
		},
		startCopySite() {
			site_name = this.dialog_copy_site.site.name;
			destination_id = this.dialog_copy_site.destination;
			site_name_destination = this.sites.filter(site => site.site_id == destination_id)[0].name;
			should_proceed = confirm("Copy site " + site_name + " to " + site_name_destination);

			if ( ! should_proceed ) {
				return;
			}

			var post_id = this.dialog_copy_site.site.site_id;

			var data = {
				'action': 'captaincore_install',
				'post_id': post_id,
				'command': 'copy',
				'value': this.dialog_copy_site.destination
			};

			self = this;

			// Start job
			description = "Coping "+ site_name + " to " + site_name_destination;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.dialog_copy_site.site = {};
					self.dialog_copy_site.show = false;
					this.dialog_copy_site.destination = "";
					this.dialog_copy_site.options = [];
					self.snackbar.message = description;
					self.snackbar.show = true;
				})
				.catch( error => console.log( error ) );

		},
		applyHttpsUrls( command ) {

			site_id = this.dialog_apply_https_urls.site_id
			site_name = this.dialog_apply_https_urls.site_name

			if ( Array.isArray( site_id ) ) { 
				environment = this.dialog_bulk.environment_selected;
			} else {
				environment = site.environment_selected
			}

			should_proceed = confirm("Will apply ssl urls to '"+site_name+"'. Proceed?");

			if ( ! should_proceed ) {
				return;
			}

			// Start job
			description = "Applying HTTPS urls to " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			var data = {
				'action': 'captaincore_install',
				'environment': environment,
				'post_id': site_id,
				'command': command,
			};

			self = this;

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.dialog_apply_https_urls.site_id = "";
					self.dialog_apply_https_urls.site_name = "";
					self.dialog_apply_https_urls.show = false;
					self.snackbar.message = "Applying HTTPS Urls";
					self.snackbar.show = true;
				});
		},
		fetchProcessLogs() {
			this.dialog_log_history.show = true;
			var data = {
				action: 'captaincore_ajax',
				command: 'fetchProcessLogs',
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_log_history.logs = response.data;
				})
				.catch( error => console.log( error ) );
		},
		showLogEntry( site_id ){
			site = this.dialog_site.site
			this.dialog_new_log_entry.show = true;
			this.dialog_new_log_entry.sites = [];
			this.dialog_new_log_entry.sites.push( site );
			this.dialog_new_log_entry.site_name = site.name;
		},
		exportTaskResults() {
			unique_name = this.dialog_job.task.job_id.substring( 0, 10 )
			this.$refs.export_task.download = `task-${unique_name}.json`;
            this.$refs.export_task.href = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify({
				description: this.dialog_job.task.description,
                results: this.dialog_job.task.stream
            }, null, 2));
            this.$refs.export_task.click();
		},
		exportTimeline() {
			this.$refs.export_json.download = "timeline.json";
            this.$refs.export_json.href = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify({
				site: { 
					name: this.dialog_site.site.name,
					site_id: this.dialog_site.site.site_id,
				},
                entries: this.dialog_site.site.timeline
            }, null, 2));
            this.$refs.export_json.click();
		},
		showLogEntryBulk() {
			this.dialog_new_log_entry.show = true;
			this.dialog_new_log_entry.sites = this.sites_selected;
			this.dialog_new_log_entry.site_name = this.sites_selected.length + " sites";
		},
		showLogEntryGeneric() {
			this.dialog_new_log_entry.show = true;
			this.dialog_new_log_entry.sites = [];
		},
		newLogEntry() {
			site_ids = this.dialog_new_log_entry.sites.map( s => s.site_id )
			var data = {
				action: 'captaincore_ajax',
				post_id: site_ids,
				process_id: this.dialog_new_log_entry.process,
				command: 'newLogEntry',
				value: this.dialog_new_log_entry.description
			}
			this.dialog_new_log_entry.show = false
			this.dialog_new_log_entry.sites = []
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					Object.keys(response.data).forEach( site_id => {
						if ( site_id == this.dialog_site.site.site_id ) {
							this.dialog_site.site.timeline = response.data[site_id]
						}
					})
					this.dialog_new_log_entry.sites = []
					this.dialog_new_log_entry.site_name = ""
					this.dialog_new_log_entry.description = ""
					this.dialog_new_log_entry.process = ""
				})
				.catch( error => console.log( error ) )
		},
		updateLogEntry() {
			site_id = this.dialog_edit_log_entry.log.websites.map( s => s.site_id )

			var data = {
				action: 'captaincore_ajax',
				command: 'updateLogEntry',
				post_id: site_id,
				log: this.dialog_edit_log_entry.log,
			};

			this.dialog_edit_log_entry.show = false
			this.dialog_edit_log_entry.sites = []

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					Object.keys(response.data).forEach( site_id => {
						if ( site_id == this.dialog_site.site.site_id ) {
							this.dialog_site.site.timeline = response.data[site_id]
						}
					})
					this.dialog_edit_log_entry.log = {}
				})
				.catch( error => console.log( error ) )
		},
		editLogEntry( site_id, log_id ) {

			// If not assigned that's fine but at least assign as string.
			if ( site_id == "" ) {
				site_id = "Not found";
			}

			if ( typeof site_id == "object" ) {
				site_id = site_id[0].site_id;
			}
			
			site = this.dialog_site.site

			var data = {
				action: 'captaincore_ajax',
				command: 'fetchProcessLog',
				post_id: site_id,
				value: log_id,
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_edit_log_entry.log = response.data;
					this.dialog_edit_log_entry.show = true;
					if ( typeof site !== "undefined" ) {
						this.dialog_edit_log_entry.site = site;
					} else {
						this.dialog_edit_log_entry.site = {};
					}
				})
				.catch( error => console.log( error ) );

		},
		viewProcess( process_id ) {

			process = this.processes.filter( process => process.process_id == process_id )[0];
			this.dialog_handbook.process = process;
			this.dialog_handbook.process.description = "Loading...";
			this.dialog_handbook.show = true;

			var data = {
				action: 'captaincore_ajax',
				post_id: process_id,
				command: 'fetchProcess',
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_handbook.process = response.data
				})
				.catch( error => console.log( error ) )

		},
		editProcess() {
			this.dialog_handbook.show = false
			var data = {
				action: 'captaincore_ajax',
				post_id: this.dialog_handbook.process.process_id,
				command: 'fetchProcessRaw',
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_edit_process.process = response.data;
					this.dialog_edit_process.show = true;
				})
				.catch( error => console.log( error ) );
		},
		saveProcess() {
			var data = {
				action: 'captaincore_ajax',
				command: 'saveProcess',
				value: this.dialog_edit_process.process
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.fetchProcesses()
					this.dialog_edit_process = { show: false, process: {} }
				})
				.catch( error => console.log( error ) )
		},
		addNewProcess() {
			var data = {
				action: 'captaincore_ajax',
				command: 'newProcess',
				value: this.new_process
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.fetchProcesses()
					this.new_process = { show: false, name: "", time_estimate: "", repeat_interval: "as-needed", repeat_quantity: "", roles: "", description: "" }
				})
				.catch( error => console.log( error ) )

		},
		addNewKey() {
			var data = {
				action: 'captaincore_ajax',
				command: 'newKey',
				value: this.new_key
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.keys.unshift( response.data );
					this.new_key = { show: false, title: "", key: "" };
					this.snackbar.message = "New SSH key added.";
					this.snackbar.show = true;
				})
				.catch( error => console.log( error ) );
		},
		viewKey( key_id ) {
			key = this.keys.filter( key => key.key_id == key_id )[0];
			this.dialog_key.key = key;
			this.dialog_key.key.key = "";
			this.dialog_key.show = true;
		},
		updateKey() {
			var data = {
				action: 'captaincore_ajax',
				command: 'updateKey',
				value: this.dialog_key.key
			};
			key = this.keys.filter( key => key.key_id == this.dialog_key.key.key_id )[0];
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.keys = this.keys.filter( key => key.key_id != this.dialog_key.key.key_id )
					this.dialog_key = { show: false, key: {} };
					this.keys.push( response.data );
					this.keys.sort((a, b) => (a.title > b.title) ? 1 : -1)
				})
				.catch( error => console.log( error ) );
		},
		deleteKey() {
			delete_key = this.keys.filter( key => key.key_id == this.dialog_key.key.key_id )[0];
			should_proceed = confirm(`Delete SSH key '${delete_key.title}'?`);
			if ( ! should_proceed ) {
				return;
			}
			var data = {
				action: 'captaincore_ajax',
				command: 'deleteKey',
				value: this.dialog_key.key.key_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.keys = this.keys.filter( key => key.key_id != this.dialog_key.key.key_id )
					this.dialog_key = { show: false, key: {} };
				})
				.catch( error => console.log( error ) );
		},
		fetchInviteInfo(){
			var data = {
				action: 'captaincore_local',
				command: 'fetchInvite',
				value: this.fetchInvite
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.new_invite = response.data
				})
				.catch( error => console.log( error ) );
		},
		updateAccount() {
			var data = {
				action: 'captaincore_local',
				command: 'updateAccount',
				value: this.profile,
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					if ( response.data.errors ) {
						this.profile.errors = response.data.errors
						return
					}
					this.snackbar.message = "Account updated."
					this.snackbar.show = true
					this.current_user_display_name = response.data.profile.display_name
					this.profile.errors = []
					this.profile.new_password = ""
				})
				.catch( error => console.log( error ) );
		},
		createAccount(){
			axios.post( '/wp-json/captaincore/v1/login/', {
					 command: "createAccount",
					 login: this.new_account,
					 invite: this.fetchInvite,
				})
				.then( response => {
					if ( response.data.errors ) {
						this.snackbar.message = response.data.errors.join(", ")
						this.snackbar.show = true
						return
					}
					this.snackbar.message = "New account created. Logging in..."
					this.snackbar.show = true
					window.location = "/account"
				})
				.catch( error => console.log( error ) );
		},
		removeAccountAccess( user_id ) {
			email = this.dialog_account.records.users.filter( u => u.user_id == user_id )[0].email
			should_proceed = confirm(`Remove access for user ${email}?`);
			if ( ! should_proceed ) {
				return;
			}
			var data = {
				action: 'captaincore_local',
				command: 'removeAccountAccess',
				value: user_id,
				account: this.dialog_account.records.account.account_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_account.records.users = this.dialog_account.records.users.filter( u => u.user_id != user_id )
					this.snackbar.message = `Removed access for user ${email}.`
					this.snackbar.show
					axios.get(
						'/wp-json/captaincore/v1/accounts', {
							headers: {'X-WP-Nonce':this.wp_nonce}
						})
						.then(response => {
							this.accounts = response.data;
						});
				})
				.catch( error => console.log( error ) );

		},
		deleteInvite( invite_id ) {
			email = this.dialog_account.records.invites.filter( i => i.invite_id == invite_id )[0].email
			should_proceed = confirm(`Delete invite ${email}?`);
			if ( ! should_proceed ) {
				return;
			}
			if ( invite_id == "" ) {
				return
			}
			var data = {
				action: 'captaincore_local',
				command: 'deleteInvite',
				value: invite_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_account.records.invites = this.dialog_account.records.invites.filter( i => i.invite_id != invite_id )
					this.snackbar.message = "Invite deleted."
					this.snackbar.show
				})
				.catch( error => console.log( error ) );
		},
		acceptInvite() {
			var data = {
				action: 'captaincore_local',
				command: 'acceptInvite',
				value: this.fetchInvite
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					window.history.pushState({}, document.title, window.location.origin + window.location.pathname );
					this.querystring = ""
					this.route = ""
					axios.get(
						'/wp-json/captaincore/v1/accounts', {
							headers: {'X-WP-Nonce':this.wp_nonce}
						})
						.then(response => {
							this.accounts = response.data;
						});
				})
				.catch( error => console.log( error ) );
		},
		cancelInvite() {
			window.history.pushState({}, document.title, window.location.origin + window.location.pathname );
			this.querystring = ""
			this.route = ""
		},
		editUser( user_id ) {
			var data = {
				action: 'captaincore_local',
				command: 'fetchUser',
				value: user_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_user.user = response.data
					this.dialog_user.show = true;
				})
				.catch( error => console.log( error ) );
		},
		saveUser() {
			var data = {
				action: 'captaincore_local',
				command: 'saveUser',
				value: this.dialog_user.user
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					if ( response.data.errors ) {
						this.dialog_user.errors = response.data.errors
						return
					}
					this.fetchAllUsers()
					this.snackbar.message = "User updated."
					this.snackbar.show = true
					this.dialog_user.show = false
					this.dialog_user.errors = []
					this.dialog_user.user = {}
				})
				.catch( error => console.log( error ) );
		},
		showAccount( account_id ) {
			account = this.accounts.filter( account => account.account_id == account_id )[0];
			var data = {
				action: 'captaincore_local',
				command: 'fetchAccount',
				value: account_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_account.records = response.data
					this.dialog_account.show = true;
					this.dialog_account.step = 2;
				})
				.catch( error => console.log( error ) );
		},
		editAccount() {
			this.dialog_edit_account.show = true
			this.dialog_edit_account.account = this.dialog_account.records.account
		},
		createSiteAccount() {
			var data = {
				action: 'captaincore_ajax',
				command: 'createSiteAccount',
				value: this.dialog_new_account.name
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.fetchAccounts()
					this.dialog_new_account.show = false
					this.dialog_new_account.name = ""
					this.dialog_account.step = 1
				})
				.catch( error => console.log( error ) );
		},
		updateSiteAccount() {
			var data = {
				action: 'captaincore_ajax',
				command: 'updateSiteAccount',
				value: this.dialog_edit_account.account
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.fetchAccounts()
					this.dialog_edit_account.show = false
					this.dialog_account.step = 1
				})
				.catch( error => console.log( error ) );
		},
		deleteAccount() {
			account = this.dialog_account.records.account
			
			should_proceed = confirm("Delete account " + account.name +"?");

			if ( ! should_proceed ) {
				return;
			}

			// Start job
			description = "Deleting account " + account.name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});
			this.dialog_site.step = 1

			var data = {
				'action': 'captaincore_ajax',
				'command': 'deleteAccount',
				'post_id': account.account_id
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data )
					// Remove item
					this.accounts = this.accounts.filter( account => account.account_id != account_id )
					this.snackbar.message = "Deleting account "+ account.name + ".";
				})
				.catch( error => console.log( error ) );
		},
		sendAccountInvite() {
			var data = {
				action: 'captaincore_local',
				command: 'sendAccountInvite',
				value: this.dialog_account.records.account.account_id,
				invite: this.dialog_account.new_invite_email
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.snackbar.message = response.data.message
					this.snackbar.show = true
					this.dialog_account.new_invite_email = "" 
					this.dialog_account.new_invite = false
					this.showAccount( this.dialog_account.records.account.account_id )
				})

		},
		editRecipe( recipe_id ) {
			recipe = this.recipes.filter( recipe => recipe.recipe_id == recipe_id )[0];
			this.dialog_cookbook.recipe = recipe;
			this.dialog_cookbook.show = true;
		},
		loadRecipe( recipe_id ) {
			recipe = this.recipes.filter( recipe => recipe.recipe_id == recipe_id )[0];
			this.snackbar.message = "Recipe '"+ recipe.title +"' loaded.";
			this.snackbar.show = true;
			this.custom_script = recipe.content;
		},
		runRecipe( recipe_id, site_id ) {
			recipe = this.recipes.filter( recipe => recipe.recipe_id == recipe_id )[0];
			site = this.dialog_site.site

			should_proceed = confirm("Run recipe '"+ recipe.title +"' on " + site.name + "?");

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'recipe',
				environment: site.environment_selected,
				value: recipe_id
			};

			description = "Run recipe '"+ recipe.title +"' on '" + site.name + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", "command": "recipe", stream: []});

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
				})
				.catch( error => console.log( error ) );
		},
		runRecipeBulk( recipe_id ){

			sites = this.sites_selected;
			site_ids = sites.map( s => s.site_id );
			recipe = this.recipes.filter( recipe => recipe.recipe_id == recipe_id )[0];

			should_proceed = confirm("Run recipe '"+ recipe.title +"' on " +  sites.length + " sites?");

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				post_id: site_ids,
				command: 'recipe',
				environment: this.dialog_bulk.environment_selected,
				value: recipe_id
			};

			description = "Run recipe '"+ recipe.title +"' on '" + sites.length + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", "command": "recipe", stream: []});

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
				})
				.catch( error => console.log( error ) );

		},
		updateRecipe() {
			var data = {
				action: 'captaincore_ajax',
				command: 'updateRecipe',
				value: this.dialog_cookbook.recipe
			};
			self = this;
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.dialog_cookbook.show = false;
					self.recipes = response.data;
				})
				.catch( error => console.log( error ) );
		},
		addRecipe() {
			var data = {
				action: 'captaincore_ajax',
				command: 'newRecipe',
				value: this.new_recipe
			};
			self = this;
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_recipe = { show: false, title: "", content: "" };
					self.recipes = response.data;
					self.new_recipe = { title: "", content: "" };
				})
				.catch( error => console.log( error ) );
		},
		viewMailgunLogs() {
			this.dialog_mailgun = { show: true, site: this.dialog_site.site, response: { items: [], pagination: [] }, loading: true, pagination: {} };
			var data = {
				action: 'captaincore_ajax',
				post_id: this.dialog_site.site.site_id,
				command: 'mailgun'
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_mailgun.loading = false;
					this.dialog_mailgun.response = response.data;
				})
				.catch( error => {
					this.snackbar.message = "Failed loading logs from Mailgun. Please try again."
					this.snackbar.show = true
					this.dialog_mailgun.loading = false
					console.log( error )
				} );
		},
		fetchMailgunPage() {
			// If we are on the last page and the number records are at max, check for new records
			if ( this.dialog_mailgun.pagination.page * 100 == this.dialog_mailgun.response.items.length ) {
				this.dialog_mailgun.loading = true;
				var data = {
					action: 'captaincore_ajax',
					post_id: this.dialog_mailgun.site.site_id,
					command: 'mailgun',
					page: this.dialog_mailgun.response.pagination["next"],
				};
				axios.post( ajaxurl, Qs.stringify( data ) )
					.then( response => {
						this.dialog_mailgun.loading = false;
						this.dialog_mailgun.response.pagination = response.data.pagination
						response.data.items.forEach( item => this.dialog_mailgun.response.items.push( item ) )
					})
					.catch( error => console.log( error ) );
			}
		},
		launchSiteDialog( site_id ) {
			site = this.sites.filter( site => site.site_id == site_id )[0];
			this.dialog_launch.site = site
			this.dialog_launch.show = true
		},
		launchSite() {

			if ( this.dialog_launch.domain == "" ) {
				this.snackbar.message = "Domain is required. Launch cancelled.";
				this.snackbar.show = true;
				return
			}

			site = this.dialog_launch.site

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'launch',
				value: this.dialog_launch.domain
			};

			description = "Lauching site '" + site.name + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", "command": "manage", stream: []});

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.dialog_launch.site = {};
					self.dialog_launch.domain = "";
					self.dialog_launch.show = false;
					self.runCommand( response.data )
				})
				.catch( error => console.log( error ) );

		},
		showCaptures( site_id ) {
			this.dialog_captures.site = this.dialog_site.site
			environment = this.dialog_site.environment_selected
			this.dialog_captures.pages = environment.capture_pages
			if ( environment.capture_pages == "" || environment.capture_pages == null ) {
				this.dialog_captures.pages = [{ page: "/" }]
			}
			this.dialog_captures.loading = true
			this.dialog_captures.show = true;
			axios.get(
				`/wp-json/captaincore/v1/site/${site_id}/${this.dialog_site.environment_selected.environment.toLowerCase()}/captures`, {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => { 
					this.dialog_captures.image_path = this.remote_upload_uri + this.dialog_site.site.site + "_" + this.dialog_site.site.site_id + "/" + this.dialog_site.environment_selected.environment.toLowerCase() + "/captures/"
					this.dialog_captures.captures = response.data
					if ( this.dialog_captures.captures.length > 0 ) {
						this.dialog_captures.capture = this.dialog_captures.captures[0]
						this.dialog_captures.selected_page = this.dialog_captures.capture.pages[0]
					}
					this.dialog_captures.loading = false
				});
		},
		switchCapture() {
			this.dialog_captures.selected_page = this.dialog_captures.capture.pages[0]
		},
		closeCaptures() {
			this.dialog_captures = { site: {}, pages: [{ page: ""}], capture: { pages: [] }, image_path:"", selected_page: "", captures: [], mode: "screenshot", loading: true, show: false, show_configure: false };
		},
		addAdditionalCapturePage() {
			this.dialog_captures.pages.push({ page: "/" });
		},
		updateCapturePages() {
			var data = {
				action: 'captaincore_ajax',
				post_id: this.dialog_captures.site.site_id,
				command: 'updateCapturePages',
				environment: this.dialog_site.environment_selected.environment,
				value: this.dialog_captures.pages,
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
			.then( response => {
				this.dialog_captures.show = false;
				this.dialog_captures.pages = [];
			})
			.catch( error => console.log( error ) );
		},
		addAdditionalPlan() {
			this.configurations.hosting_plans.push( {"name":"","price":"","limits":{"visits":"","storage":"","sites":""}})
		},
		deletePlan(index) {
			this.configurations.hosting_plans.splice( index, 1 )
		},
		toggleSite( site_id ) {
			site = this.sites.filter( site => site.site_id == site_id )[0];
			this.dialog_toggle.show = true;
			this.dialog_toggle.site_id = site.site_id;
			this.dialog_toggle.site_name = site.name;
			this.dialog_toggle.business_name = this.business_name;
			this.dialog_toggle.business_link = this.business_link;
		},
		toggleSiteBulk() {
			sites = this.sites_selected
			site_ids = this.sites_selected.map( s => s.site_id )
			site_name = sites.length + " sites";
			this.dialog_toggle.show = true;
			this.dialog_toggle.site_id = site_ids;
			this.dialog_toggle.site_name = site_name;
			this.dialog_toggle.business_name = this.business_name;
			this.dialog_toggle.business_link = this.business_link;
		},
		resetPermissions( site_id ) {
			site = this.dialog_site.site;
			should_proceed = confirm("Reset file permissions to defaults " + site.name + "?");
			description = "Resetting file permissions to defaults on '" + site.name + "'";

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				environment: site.environment_selected,
				post_id: site_id,
				command: 'reset-permissions'
			};
			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data )
					this.snackbar.message = description;
					this.snackbar.show = true;
				})
				.catch( error => console.log( error ) );
		},
		showSite( site ) {
			this.users_search = ""
			this.dialog_site.loading = true
			this.fetchSiteEnvironments( site.site_id )
			this.fetchSiteDetails( site.site_id )
			show_site = JSON.parse ( JSON.stringify ( site ) )
			show_site.usage_breakdown = []
			show_site.pagination = []
			show_site.pagination['sortBy'] = 'roles'
            show_site.users = []
            show_site.update_logs = []
            show_site.timeline = []
			show_site.shared_with = []
            show_site.loading = false
			this.dialog_site.site = show_site
			this.dialog_site.step = 2
			this.dialog_new_site = {
				provider: "kinsta",
				show: false,
				show_vars: false,
				environment_vars: [],
				saving: false,
				domain: "",
				key: "",
				site: "",
				errors: [],
				shared_with: [],
				account_id: "",
				environments: [
					{"environment": "Production", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" },
					{"environment": "Staging", "site": "", "address": "","username":"","password":"","protocol":"sftp","port":"2222","home_directory":"",updates_enabled: "1","offload_enabled": false,"offload_provider":"","offload_access_key":"","offload_secret_key":"","offload_bucket":"","offload_path":"" }
				],
			}
		},
		copySSH( site ) {
			var data = {
				'action': 'captaincore_ajax',
				'command': "fetch-site-environments",
				'post_id': site.site_id
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.copyText( response.data[0].ssh )
				});
			
		},
		scanErrors( site ) {
			site.loading = true

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'scan-errors',
			};

			description = "Scanning " + site.name + " for errors";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({ "job_id": job_id, "description": description, "status": "queued", stream: [], "command": "scanErrors", "site_id": site.site_id });

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with responsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
				})
				.catch( error => console.log( error ) );
		},
		showSiteMigration( site_id ){
			site = this.dialog_site.site;
			this.dialog_migration.sites.push( site );
			this.dialog_migration.show = true;
			this.dialog_migration.site_id = site.site_id
			this.dialog_migration.site_name = site.name;
		},
		validateSiteMigration() {
			if ( this.$refs.formSiteMigration.validate() ) {
				this.siteMigration( this.dialog_migration.site_id );
			}	
		},
		siteMigration( site_id ) {
			site = this.dialog_site.site;
			site_name = site.name;

			should_proceed = confirm("Migrate from backup url? This will overwrite the existing site at " + site_name + ".");
			description = "Migrating backup to '" + site_name + "'";

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				post_id: site_id,
				command: 'migrate',
				value: this.dialog_migration.backup_url,
				update_urls: this.dialog_migration.update_urls,
				environment: site.environment_selected
			};

			self = this;
			description = "Migrating backup to '" + site_name + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", "command": "migrate", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
			.then( response => {
				self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
				self.runCommand( response.data )
				self.snackbar.message = "Migration backup to " + site_name;
				self.snackbar.show = true;
				self.dialog_migration.show = false;
				self.dialog_migration.sites = [];
				self.dialog_migration.backup_url = "";
				self.dialog_migration.update_urls = "";
			})
			.catch( error => console.log( error ) );

		},
		DeactivateSite( site_id ) {

			site = this.dialog_site.site;
			site_name = this.dialog_toggle.site_name;

			if ( Array.isArray( site_id ) ) { 
				environment = this.dialog_bulk.environment_selected;
			} else {
				environment = site.environment_selected
			}

			var data = {
				action: 'captaincore_install',
				post_id: site_id,
				command: 'deactivate',
				environment: environment,
				name: this.dialog_toggle.business_name,
				link: this.dialog_toggle.business_link
			};

			self = this;
			description = "Deactivating '" + site_name + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
					self.snackbar.message = "Deactivating " + site_name;
					self.snackbar.show = true;
					self.dialog_toggle.show = false;
					self.dialog_toggle.site_id = "";
					self.dialog_toggle.site_name = "";
					self.dialog_toggle.business_name = "";
					self.dialog_toggle.business_link = "";
				})
				.catch( error => console.log( error ) );

		},
		ActivateSite( site_id ) {

			site = this.dialog_site.site;
			site_name = this.dialog_toggle.site_name;

			if ( Array.isArray( site_id ) ) { 
				environment = this.dialog_bulk.environment_selected;
			} else {
				environment = site.environment_selected
			}

			var data = {
				action: 'captaincore_install',
				post_id: site_id,
				environment: environment,
				command: 'activate'
			};

			self = this;
			description = "Activating '" + site_name + "'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
					self.snackbar.message = "Activating " + site_name;
					self.snackbar.show = true;
					self.dialog_toggle.show = false;
					self.dialog_toggle.site_id = "";
					self.dialog_toggle.site_name = "";
					self.dialog_toggle.business_name = "";
					self.dialog_toggle.business_link = "";
				})
				.catch( error => console.log( error ) );

		},
		siteDeploy( site_id ) {

			site = this.dialog_site.site
			should_proceed = confirm("Deploy defaults on " + site.name + "?")
			description = "Deploy defaults on '" + site.name + "'"

			if ( ! should_proceed ) {
				return
			}

			var data = {
				action: 'captaincore_install',
				environment: site.environment_selected,
				post_id: site_id,
				command: 'deploy-defaults'
			};

			// Start job
			job_id = Math.round((new Date()).getTime())
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []})

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data
					this.runCommand( response.data )
					this.snackbar.message = description
					this.snackbar.show = true
				})
				.catch( error => console.log( error ) );

		},
		siteDeployBulk(){

			sites = this.sites_selected
			site_ids = sites.map( s => s.site_id )
			should_proceed = confirm("Deploy defaults on " + sites.length + " sites?")
			description = "Deploying defaults on '" + sites.length + " sites'"

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				environment: this.dialog_bulk.environment_selected,
				post_id: site_ids,
				command: 'deploy-defaults'
			}

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id ,"site_id": site_ids, "command": "manage", "description": description, "status": "queued", stream: []})

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data
					this.runCommand( response.data )
					this.snackbar.message = description
					this.snackbar.show = true
				})
				.catch( error => console.log( error ) )

		},
		runCustomCode( site_id ) {

			site = this.dialog_site.site;
			should_proceed = confirm("Deploy custom code on "+site.name+"?");

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				environment: site.environment_selected,
				post_id: site_id,
				command: 'run',
				value: this.custom_script,
				background: true
			};

			self = this;
			description = "Deploying custom code on '" + site.name  +"'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id, "description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
					self.snackbar.message = description;
					self.snackbar.show = true;
					self.custom_script = "";
				})
				.catch( error => console.log( error ) );

		},
		runCustomCodeBulk(){

			sites = this.sites_selected;
			site_ids = sites.map( s => s.site_id );
			should_proceed = confirm("Deploy custom code on "+ sites.length +" sites?");

			if ( ! should_proceed ) {
				return;
			}

			wp_cli = this.custom_script;

			var data = {
				action: 'captaincore_install',
				environment: this.dialog_bulk.environment_selected,
				post_id: site_ids,
				command: 'run',
				value: this.custom_script,
				background: true
			};

			self = this;
			description = "Deploying custom code on '" + sites.length + " sites'";

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id, "description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
					self.snackbar.message = description;
					self.snackbar.show = true;
					self.custom_script = "";
				})
				.catch( error => console.log( error ) );

		},
		fetchTimeline( site_id ) {
			var data = {
				action: 'captaincore_ajax',
				post_id: site_id,
				command: 'timeline'
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_site.site.timeline = response.data
				})
				.catch( error => console.log( error ) );
		},
		addEnvironmentVarNewSite() {
			this.dialog_new_site.environment_vars.push({ key: '', value: '' })
		},
		removeEnvironmentVarNewSite( index ) {
			this.dialog_new_site.environment_vars.splice( index, 1 )
		},
		addEnvironmentVar() {
			this.dialog_edit_site.site.environment_vars.push({ key: '', value: '' })
		},
		removeEnvironmentVar( index ) {
			this.dialog_edit_site.site.environment_vars.splice( index, 1 )
		},
		addDefaultsUser() {
			this.dialog_account.records.account.defaults.users.push({ email: "", first_name: "", last_name: "", role: "administrator", username: "" })
		},
		addGlobalDefaultsUser() {
			this.defaults.users.push({ email: "", first_name: "", last_name: "", role: "administrator", username: "" })
		},
		addDomain() {
			this.dialog_new_domain.loading = true;
			this.dialog_new_domain.errors  = [];

			var data = {
				action: 'captaincore_ajax',
				command: 'addDomain',
				value: this.dialog_new_domain.domain
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// If error then response
					if ( response.data.errors ) {
						this.dialog_new_domain.loading = false
						this.dialog_new_domain.errors = response.data.errors;
						return;
					}
					this.dialog_new_domain.loading = false;
					this.dialog_new_domain = { show: false, domain: { name: "", customer: "" } };
					this.domains.push( response.data )
					this.domains.sort((a, b) => (a.name > b.name) ? 1 : -1)
					this.snackbar.message = "Added new domain " + response.data.name;
					this.snackbar.show = true;
				})
				.catch( error => {
					this.snackbar.message = error;
					this.snackbar.show = true;
					this.dialog_new_domain.loading = false;
				});
		},
		addRecord() {
			timestamp = new Date().getTime();
			this.dialog_domain.records.push({ id: "new_" + timestamp, edit: false, delete: false, new: true, ttl: "3600", type: "A", value: [{"value": ""}], update: {"record_id": "new_" + timestamp, "record_type": "A", "record_name": "", "record_value": [{"value": ""}], "record_ttl": "3600", "record_status": "new-record" } });
		},
		addRecordValue( index ) {
			record = this.dialog_domain.records[index];
			if ( record.type == "A" || record.type == "AAAA" || record.type == "ANAME" || record.type == "TXT" || record.type == "SPF" ) {
				record.update.record_value.push({ value: "" });
			}
			if ( record.type == "MX" ) {
				record.update.record_value.push({ level: "", value: "" });
			}
			if ( record.type == "SRV" ) {
				record.update.record_value.push({ priority: 0, weight: 0, port: 443, value: "" });
			}
		},
		viewRecord( record_id ){
			record = this.dialog_domain.records.filter( r => r.id == record_id )[0];
			record.edit = false
			record.delete = false
		},
		editRecord( record_id ){
			record = this.dialog_domain.records.filter( r => r.id == record_id )[0];
			record.edit = true
			record.delete = false
		},
		changeRecordType( index ) {
			record = this.dialog_domain.records.filter( (r, i) => i == index )[0];
			if ( record.type == "A" || record.type == "AAAA" || record.type == "ANAME" || record.type == "TXT" || record.type == "SPF" ) {
				record.update.record_value = [{ value: "" }];
			}
			if ( record.type == "MX" ) {
				record.update.record_value = [{ level: "", value: "" }];
			}
			if ( record.type == "SRV" ) {
				record.update.record_value = [{ priority: 0, weight: 0, port: 443, value: "" }];
			}
			if ( record.type == "CNAME" || record.type == "HTTPRedirection" ) {
				record.update.record_value = "";
			}
		},
		deleteUserValue( delete_index ) {
			this.dialog_account.records.account.defaults.users = this.dialog_account.records.account.defaults.users.filter( (u, index) => index != delete_index )
		},
		deleteGlobalUserValue( delete_index ) {
			this.defaults.users = this.defaults.users.filter( (u, index) => index != delete_index )
		},
		deleteRecordValue( index, value_index ) {
			this.dialog_domain.records[index].update.record_value.splice( value_index, 1 )
		},
		deleteCurrentRecord( record_id ){
			record = this.dialog_domain.records.filter( r => r.id == record_id )[0];
			record.edit = false
			record.delete = !record.delete
		},
		deleteRecord( index ){
			this.dialog_domain.records.splice( index, 1 )
		},
		modifyDNS( domain ) {
			this.dialog_domain = { show: false, show_import: false, import_json: "", domain: {}, records: [], loading: true, saving: false, step: 2 };
			if ( domain.remote_id == null ) {
				this.dialog_domain.errors = [ "Domain not found." ];
				this.dialog_domain.domain = domain;
				this.dialog_domain.loading = false
				this.dialog_domain.show = true;
				return
			}
			axios.get(
				'/wp-json/captaincore/v1/domain/' + domain.domain_id, {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					if ( typeof response.data == "string" ) {
						this.dialog_domain.errors = [ response.data ];
						this.dialog_domain.loading = false
						return
					}

					if ( typeof response.data.errors == 'object' ) {
						this.dialog_domain.loading = false
						this.dialog_domain.errors = response.data.errors
						return
					}

					// Prep records with 
					response.data.forEach( r => {
						if ( r.type == "A" || r.type == "AAAA" ) {
							new_value = [];
							r.value.forEach( v => {
								new_value.push({ "value": v });
							});
							r.value = new_value;
						}
						r.update = {
							"record_id": JSON.parse(JSON.stringify(r.id)),
							"record_type": JSON.parse(JSON.stringify(r.type)),
							"record_name": JSON.parse(JSON.stringify(r.name)),
							"record_value": JSON.parse(JSON.stringify(r.value)),
							"record_ttl": JSON.parse(JSON.stringify(r.ttl)),
							"record_status": "edit-record"
						};
						r.edit = false;
						r.delete = false;
					});
					timestamp = new Date().getTime();
					response.data.push({ id: "new_" + timestamp, edit: false, delete: false, new: true, ttl: "3600", type: "A", value: [{"value": ""}], update: {"record_id": "new_" + timestamp, "record_type": "A", "record_name": "", "record_value": [{"value": ""}], "record_ttl": "3600", "record_status": "new-record" } });
					this.dialog_domain.records = response.data;
					this.dialog_domain.loading = false;
				});
			this.dialog_domain.domain = domain;
			this.dialog_domain.show = true;
			
		},
		importDomain() {
			// Remove any pending new records
			this.dialog_domain.records = this.dialog_domain.records.filter( record => ! record.new )
			// Mark existing records to be deleted
			this.dialog_domain.records.forEach( record => {
				record.delete = true
			})
			// Process records to be imported and mark as new
			import_json = JSON.parse( this.dialog_domain.import_json )
			import_json.records.forEach( record => {
				record.new = true
				record.update.record_status = "new-record"
				this.dialog_domain.records.push( record )
			})
			this.dialog_domain.import_json = ""
			this.dialog_domain.show_import = false
			this.addRecord()
			this.snackbar.message = "Loaded DNS records from import. Review then save records."
			this.snackbar.show = true
		},
		exportDomain() {
			this.$refs.export_domain.download = `dns_records_${this.dialog_domain.domain.name}.json`;
			export_records = this.dialog_domain.records.filter( record => ! record.new )
            this.$refs.export_domain.href = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify({
				records: export_records
            }, null, 2));
            this.$refs.export_domain.click();
		},
		deleteDomain() {
			should_proceed = confirm("Delete domain " +  this.dialog_domain.domain.name + "?");
			if ( ! should_proceed ) {
				return;
			}
			this.dialog_domain.loading = true
			var data = {
				action: 'captaincore_ajax',
				command: 'deleteDomain',
				value: this.dialog_domain.domain.domain_id
			}
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.domains = this.domains.filter( d => d.domain_id != response.data.domain_id );
					this.dialog_domain = { show: false, show_import: false, import_json: "", domain: {}, records: [], loading: true, saving: false };
					this.snackbar.message = response.data.message;
					this.snackbar.show = true;
				})
				.catch( error => {
					this.snackbar.message = error;
					this.snackbar.show = true;
					this.dialog_domain.loading = false;
				});
		},
		saveDNS() {
			this.dialog_domain.saving = true;
			domain_id = this.dialog_domain.domain.remote_id;
			record_updates = [];

			this.dialog_domain.records.forEach( record => {
				// Format value for API
				if ( record.type != "CNAME" && record.type != "HTTPRedirection" ) {
					record_value = [];
					record.update.record_value.forEach( v => {
						if ( v.value == "" ) {
							return
						}
						v.value = v.value.trim();
						record_value.push( v );
					});
				}

				if ( record.type == "CNAME" ) {
					// Check for value ending in period. If not add one.
					record_value = record.update.record_value.trim();
					if ( record_value.substr(record_value.length - 1) != "." ) {
						record_value = record_value + ".";
					}
				}

				if ( record.type == "MX" ) {
					// Check for value ending in period. If not add one.
					record.update.record_value.forEach( v => {
						v.value = v.value.trim();
						if ( v.value.substr(v.value.length - 1) != "." ) {
							v.value = v.value + ".";
						}
					})
				}

				if ( record.type == "TXT" ) {
					// Check for value wrapped in quotes. If not add them.
					record.update.record_value.forEach( v => {
						v.value = v.value.trim();
						if ( v.value.substr(0,1) != '"' ) {
							v.value = '"' + v.value;
						}
						if ( v.value.substr(v.value.length - 1) != '"' ) {
							v.value = v.value + '"';
						}
					})
				}

				if ( record.type == "HTTPRedirection" ) {
					record_value = record.update.record_value.trim();
				}

				// Clean out empty values
				if ( record.update.record_type == "A" && record_value.length == 0 ) {
					return;
				}
				
				// Clean out empty values
				if ( record.update.record_type == "CNAME" && record.update.record_value == "" ) {
					return;
				}

				// Prepares new records
				if ( record.new ) {
					record.update.record_type = record.type;
				}
				
				// Prepares new & modified records
				if ( record.edit || record.new ) {
					record.update.record_value = record_value;
					record_updates.push( record.update );
				}

				// Prepares records to be removed
				if ( record.delete ) {
					record_updates.push({
						"record_id": record.id,
						"record_type": record.type,
						"record_name": record.name,
						"record_value": record_value,
						"record_ttl": record.ttl,
						"record_status": "remove-record"
					});
				}
			});
			
			if ( record_updates.length == 0 ) {
				this.snackbar.message = "No record changes found.";
				this.snackbar.show = true;
				this.dialog_domain.saving = false;
				return;
			}

			var data = {
				'action': 'captaincore_dns',
				'domain_key': domain_id,
				'record_updates': record_updates
			};

			self = this;
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.dialog_domain.results = response.data;
					self.reflectDNS();
					
					// If no errors found then fetch new details
					// self.modifyDNS( self.dialog_domain.domain );
				})
				.catch( error => {
					self.snackbar.message = error;
					self.snackbar.show = true;
					self.dialog_domain.saving = false;
					//self.dialog_domain.results = response.data;
				});
		},
		reflectDNS() {
			this.dialog_domain.results.forEach( result => {

				if ( result.success && result.success == "Record  updated successfully" ) {
					record = this.dialog_domain.records.filter( r => r.id == result.record_id )[0];
					record.edit = false;
					record.name = JSON.parse(JSON.stringify( record.update.record_name ));
					record.value = JSON.parse(JSON.stringify( record.update.record_value ));
					record.ttl = JSON.parse(JSON.stringify( record.update.record_ttl ));
				}

				if ( result.success && result.success == "Record  deleted successfully" ) {
					this.dialog_domain.records = this.dialog_domain.records.filter( record => result.record_id != record.id );
				}

				// Add new record
				if ( typeof result.success == 'undefined' && typeof result.errors == 'undefined' && result.id != "" ) {

					result.success = "Record added successfully";

					// Removed existing new recording matching type, name, value and ttl.
					this.dialog_domain.records = this.dialog_domain.records.filter( r => r.update.record_status != "new-record" && r.update.record_name != result.name )

					if ( result.type == "A" || result.type == "AAAA" || result.type == "SPF" ) {
						record_value = [];
						result.value.forEach( r => {
							record_value.push({ value: r });
						});
					} else {
						record_value = result.value;
					}

					result.new = false
					result.edit = false
					result.delete = false
					result.value = JSON.parse(JSON.stringify(record_value))
					result.update = {
						"record_id": JSON.parse(JSON.stringify(result.id)),
						"record_type": JSON.parse(JSON.stringify(result.type)),
						"record_name": JSON.parse(JSON.stringify(result.name)),
						"record_value": JSON.parse(JSON.stringify(record_value)),
						"record_ttl": JSON.parse(JSON.stringify(result.ttl)),
						"record_status": "edit-record"
					}

					// Add new record
					this.dialog_domain.records.push( result );

					// Sort new results
					this.dialog_domain.records.sort(function (record1, record2) {

						// Sort by types
						// If the first item has a higher number, move it down
						// If the first item has a lower number, move it up
						if (record1.type < record2.type) return -1;
						if (record1.type > record2.type) return 1;

						// If the votes number is the same between both items, sort alphabetically
						// If the first item comes first in the alphabet, move it up
						// Otherwise move it down
						if (record1.name > record2.name) return 1;
						if (record1.name < record2.name) return -1;

					});
				}

				this.dialog_domain.saving = false;

			});
		},
		modifyPlan() {
			this.dialog_modify_plan.hosting_plans = JSON.parse(JSON.stringify( this.configurations.hosting_plans ))
			this.dialog_modify_plan.hosting_plans.push( {"name":"Custom","interval":"12","price":"","limits":{"visits":"","storage":"","sites":""}} )
			this.dialog_modify_plan.plan = JSON.parse(JSON.stringify( this.dialog_account.records.account.plan ))
			// Adds commas
			if ( this.dialog_modify_plan.plan.limits.visits != null ) {
				this.dialog_modify_plan.plan.limits.visits = this.dialog_modify_plan.plan.limits.visits.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
			}
			this.dialog_modify_plan.selected_plan = JSON.parse(JSON.stringify( this.dialog_account.records.account.plan.name ) )
			this.dialog_modify_plan.customer_name = this.dialog_site.site.account.name;
			this.dialog_modify_plan.show = true;
		},
		editPlan() {
			this.dialog_modify_plan.plan = Object.assign({}, this.dialog_account.records.account.plan)
			// Adds commas
			if ( this.dialog_modify_plan.plan.limits.visits != null ) {
				this.dialog_modify_plan.plan.limits.visits  = this.dialog_modify_plan.plan.limits.visits.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
			this.dialog_modify_plan.selected_plan = this.dialog_account.records.account.plan.name;
			this.dialog_modify_plan.customer_name = this.dialog_account.records.account.name;
			this.dialog_modify_plan.show = true;
		},
		updatePlan() {
			account_id = this.dialog_account.records.account.account_id
			plan = Object.assign( {}, this.dialog_modify_plan.plan )

			// Remove commas
			plan.limits.visits = plan.limits.visits.replace(/,/g, '')
			this.dialog_account.records.account.plan.limits = plan.limits
			this.dialog_account.records.account.plan.name = plan.name
			this.dialog_account.records.account.plan.price = plan.price
			this.dialog_modify_plan.show = false;
			
			// Prep AJAX request
			var data = {
				'action': 'captaincore_ajax',
				'post_id': account_id,
				'command': "updatePlan",
				'value': { "plan": plan },
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.dialog_modify_plan = { show: false, site: {}, hosting_plans: [], selected_plan: "", plan: { limits: {}, addons: [] }, customer_name: "", interval: "12" },
					this.showAccount( account_id )
			});

		},
		addAddon() {
			this.dialog_modify_plan.plan.addons.push({ "name": "", "quantity": "", "price": "" });
		},
		removeAddon( remove_item ) {
			this.dialog_modify_plan.plan.addons = this.dialog_modify_plan.plan.addons.filter( (item, index) => index != remove_item );
		},
		loadHostingPlan() {
			current_interval = JSON.parse(JSON.stringify( this.dialog_modify_plan.plan.interval ) )
			current_addons = JSON.parse(JSON.stringify( this.dialog_modify_plan.plan.addons ) )
			selected_plan = this.dialog_modify_plan.selected_plan
			hosting_plan = this.dialog_modify_plan.hosting_plans.filter( plan => plan.name == selected_plan )[0]
			if ( typeof hosting_plan != "undefined" ) {
				this.dialog_modify_plan.plan = JSON.parse(JSON.stringify( hosting_plan ))
			}
			if ( current_interval != hosting_plan.interval ) {
				this.dialog_modify_plan.plan.interval = current_interval
				this.dialog_modify_plan.plan.addons = current_addons
				this.calculateHostingPlan()
			}
		},
		calculateHostingPlan() {
			original_plan = this.dialog_modify_plan.hosting_plans.filter( p => p.name == this.dialog_modify_plan.selected_plan )[0]
			if ( this.dialog_modify_plan.plan.interval == original_plan.interval ) {
				this.dialog_modify_plan.plan.price = JSON.parse(JSON.stringify( original_plan.price ))
			} else {
				unit_price = original_plan.price / original_plan.interval
				this.dialog_modify_plan.plan.price = unit_price * this.dialog_modify_plan.plan.interval
			}
		},
		PushProductionToStaging( site_id ) {
			site = this.dialog_site.site;
			should_proceed = confirm("Push production site " + site.name + " to staging site?");
			description = "Pushing production site '" + site.name + "' to staging";

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'production-to-staging',
				value: this.current_user_email
			};

			self = this;

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = description;
					self.snackbar.show = true;
				})
				.catch( error => console.log( error ) );
		},
		PushStagingToProduction( site_id ) {

			site = this.dialog_site.site;
			should_proceed = confirm("Push staging site " + site.name + " to production site?");
			description = "Pushing staging site '" + site.name + "' to production";

			if ( ! should_proceed ) {
				return;
			}

			var data = {
				action: 'captaincore_install',
				post_id: site.site_id,
				command: 'staging-to-production',
				value: this.current_user_email
			};

			self = this;

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = description;
					self.snackbar.show = true;
				})
				.catch( error => console.log( error ) );
		},
		viewApplyHttpsUrls( site_id ) {
			site = this.dialog_site.site;
			this.dialog_apply_https_urls.show = true;
			this.dialog_apply_https_urls.site_id = site_id
			this.dialog_apply_https_urls.site_name = site.name;
		},
		viewApplyHttpsUrlsBulk() {
			this.dialog_apply_https_urls.show = true;
			this.dialog_apply_https_urls.site_id = this.sites_selected.map( s => s.site_id );
			this.dialog_apply_https_urls.site_name = this.sites_selected.length + " sites";
		},
		RollbackQuicksave( site_id, quicksave_id, addon_type, addon_name ){
			site = this.dialog_site.site;
			environment = this.dialog_site.environment_selected;
			quicksave = environment.quicksaves.filter( quicksave => quicksave.quicksave_id == quicksave_id )[0];
			date = this.$options.filters.pretty_timestamp_epoch(quicksave.created_at);
			description = "Rollback "+ addon_type + " " + addon_name +" to version as of " + date + " on " + site.name ;
			should_proceed = confirm( description + "?");

			if ( ! should_proceed ) {
				return;
			}

			site = this.dialog_site.site;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'environment': site.environment_selected,
				'quicksave_id': quicksave_id,
				'command': 'rollback',
				'value'	: addon_name,
				'addon_type': addon_type,
			};

			self = this;

			// Start job
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = "Rollback in progress.";
					self.snackbar.show = true;
				})
				.catch( error => console.log( error ) );

		},
		QuicksaveFileRestore() {

			date = this.$options.filters.pretty_timestamp_epoch(this.dialog_file_diff.quicksave.created_at);
			should_proceed = confirm("Rollback file " + this.dialog_file_diff.file_name  + " as of " + date);

			if ( ! should_proceed ) {
				return;
			}

			site_id = this.dialog_file_diff.quicksave.site_id
			site = this.dialog_site.site;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'environment': site.environment_selected,
				'quicksave_id': this.dialog_file_diff.quicksave.quicksave_id,
				'command': 'quicksave_file_restore',
				'value'	: this.dialog_file_diff.file_name,
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.snackbar.message = "File restore in process. Will email once completed.";
					self.snackbar.show = true;
					self.dialog_file_diff.show = false;
				})
				.catch( error => console.log( error ) );

		},
		QuicksaveFileDiff( site_id, quicksave_id, git_commit, file_name ) {
			site = this.dialog_site.site;
			environment = this.dialog_site.environment_selected;
			file_name = file_name.split("	")[1];
			this.dialog_file_diff.response = "";
			this.dialog_file_diff.file_name = file_name;
			this.dialog_file_diff.loading = true;
			this.dialog_file_diff.quicksave = environment.quicksaves.filter(quicksave => quicksave.quicksave_id == quicksave_id)[0];
			this.dialog_file_diff.show = true;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'environment': site.environment_selected,
				'quicksave_id': quicksave_id,
				'command': 'quicksave_file_diff',
				'commit': git_commit,
				'value'	: file_name,
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					html = [];
					response.data.split('\n').forEach(line => {
						applied_css="";
						if ( line[0] == "-" ) {
							applied_css=" class='red lighten-4'";
						}
						if ( line[0] == "+" ) {
							applied_css=" class='green lighten-5'";
						}
						html.push("<div"+applied_css+">" + line + "</div>");
					});
					self.dialog_file_diff.response = html.join('\n');
					self.dialog_file_diff.loading = false;
				})
				.catch( error => console.log( error ) );

		},
		QuicksaveCheck( site_id ) {

			site = this.dialog_site.site;
			should_proceed = confirm("Run a manual check for new files on " + site.name + "?");

			if ( ! should_proceed ) {
				return;
			}

			// Start job
			site_name = site.name;
			description = "Checking for file changes on " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': 'quick_backup',
				'environment': site.environment_selected,
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = "Quicksave in process.";
					self.snackbar.show = true;
					
				})
				.catch( error => console.log( error ) );

		},
		QuicksavesRollback( site_id, quicksave ) {

			date = this.$options.filters.pretty_timestamp_epoch(quicksave.created_at);
			site = this.dialog_site.site;
			should_proceed = confirm("Will rollback all themes/plugins on " + site.name + " to " + date + ". Proceed?");

			if ( ! should_proceed ) {
				return;
			}

			// Start job
			description = "Quicksave rollback all themes/plugins on " + site.name + " to " + date + ".";
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			var data = {
				'action': 'captaincore_install',
				'post_id': quicksave.site_id,
				'quicksave_id': quicksave.quicksave_id,
				'command': 'quicksave_rollback',
				'environment': site.environment_selected,
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
			  .then( response => {
					quicksave.loading = false;
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = "Rollback in process.";
					self.snackbar.show = true;
				})
			  .catch( error => console.log( error ) );

		},
		viewQuicksavesChanges( site_id, quicksave ) {

			site = this.dialog_site.site;
			quicksave.view_changes = true;

			var data = {
				action: 'captaincore_install',
				post_id: site_id,
				command: 'view_quicksave_changes',
				environment: site.environment_selected,
				value: quicksave.git_commit
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
			  .then( response => {
					// Remove empty last row
					quicksave.view_files = response.data.trim().split("\n");
					quicksave.filtered_files = response.data.trim().split("\n");
					quicksave.loading = false;
				})
			  .catch( error => console.log( error ) );
		},
		expandQuicksave( item, site_id, environment ) {
			table_name = "quicksave_table_" + site_id + "_" + environment;
			if ( typeof this.$refs[table_name].expansion[item.quicksave_id] == 'boolean' ) {
				this.$refs[table_name].expansion = ""
			} else {
				this.$refs[table_name].expansion = { [item.quicksave_id] : true }
			}
		},
		viewQuicksaves() {
			axios.get(
				'/wp-json/captaincore/v1/site/'+this.dialog_site.site.site_id+'/quicksaves/'+this.dialog_site.environment_selected.environment.toLowerCase(), {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => { 
					this.dialog_site.environment_selected.quicksaves = response.data
				});
		},
		downloadBackup( backup_id, backup_tree ) {
			directories = []
			site_id = this.dialog_site.site.site_id

			files = backup_tree.map( item => item.path )
			backup_tree.forEach ( item => {
				if ( item.type == "dir" && item.size > 1 ) {
					directories.push( item.path )
				}
			})
			
			description = "Generating downloadable zip for " + backup_tree.map( item => item.count ).reduce((a, b) => a + b, 0) + " items. Will send an email when ready."
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: [], "command": "downloadBackup"})

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "backup_download",
				'value': {
					files: JSON.stringify( files ),
					directories: JSON.stringify( directories ),
					backup_id: backup_id,
				},
				'environment': this.dialog_site.environment_selected.environment,
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.snackbar.message = description
					this.snackbar.show = true
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
			});
		},
		getBackup( backup_id, site_id ) {
			environment = this.dialog_site.environment_selected.environment.toLowerCase()
			axios.get(
				`/wp-json/captaincore/v1/site/${site_id}/${environment}/backups/${backup_id}`, {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					backup_selected = this.dialog_site.environment_selected.backups.filter( b => b.id == backup_id )
					if ( backup_selected.length != 1 ) {
						return
					}
					backup_selected[0].files = response.data.files
					backup_selected[0].omitted = response.data.omitted
					this.sortTree( backup_selected[0].files )
					backup_selected[0].loading = false
				});
		},
		viewBackups() {
			site_id = this.dialog_site.site.site_id
			environment = this.dialog_site.environment_selected.environment.toLowerCase()
			axios.get(
				`/wp-json/captaincore/v1/site/${site_id}/${environment}/backups`, {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => {
					this.dialog_site.environment_selected.backups = response.data
				});
		},
		expandBackup( item, site_id, environment ) {
			table_name = "backup_table_" + site_id + "_" + environment;
			if ( typeof this.$refs[table_name].expansion[item.id] == 'boolean' ) {
				this.$refs[table_name].expansion = ""
			} else {
				this.getBackup( item.id, site_id )
				this.$refs[table_name].expansion = { [item.id] : true }
			}
		},
		viewSnapshots() {
			site = this.dialog_site.site
			axios.get(
				'/wp-json/captaincore/v1/site/'+ site.site_id +'/snapshots', {
					headers: {'X-WP-Nonce':this.wp_nonce}
				})
				.then(response => { 
						site.environments[0].snapshots = response.data.Production
						site.environments[1].snapshots = response.data.Staging				
				});
		},
		activateTheme( theme_name, site_id ) {

			site = this.dialog_site.site

			// Enable loading progress
			site.loading_themes = true;
			this.dialog_site.environment_selected.themes.filter(theme => theme.name != theme_name).forEach( theme => theme.status = "inactive" );

			// Start job
			site_name = site.name;
			description = "Activating theme '" + theme_name + "' on " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			// WP ClI command to send
			wpcli = "wp theme activate " + theme_name;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					site.loading_themes = false;
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
			});
		},
		deleteTheme (theme_name, site_id) {

			should_proceed = confirm("Are you sure you want to delete theme " + theme_name + "?");

			if ( ! should_proceed ) {
				return;
			}

			site = this.dialog_site.site;

			// Enable loading progress
			site.loading_themes = true;
			description = "Deleting theme '" +theme_name + "' from " + site.name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			// WP ClI command to send
			wpcli = "wp theme delete " + theme_name;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					environment = this.dialog_site.environment_selected
					updated_themes = environment.themes.filter(theme => theme.name != theme_name);
					environment.themes = updated_themes;
					site.loading_themes = false;
					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
			});

		},
		addPlugin ( site_id ){
			site = this.dialog_site.site
			this.new_plugin.show = true;
			this.new_plugin.sites.push( site );
			this.new_plugin.site_name = site.name;
			this.new_plugin.current_plugins = this.dialog_site.environment_selected.plugins.map( p => p.name );
			this.new_plugin.environment_selected = site.environment_selected;
			this.fetchPlugins();
		},
		addPluginBulk() {
			this.new_plugin.show = true;
			this.new_plugin.sites = this.sites_selected;
			this.new_plugin.site_name = this.new_plugin.sites.length + " sites";
			this.new_plugin.current_plugins = [];
			this.new_plugin.environment_selected = this.dialog_bulk.environment_selected;
			this.fetchPlugins();
		},
		installPlugin ( plugin ) {
			if ( this.new_plugin.sites.length ==  1 ) {
				site_id = this.new_plugin.sites[0].site_id;
				environment_selected = this.new_plugin.sites[0].environment_selected
			} else {
				site_id = this.new_plugin.sites.map( s => s.site_id )
				environment_selected = this.new_plugin.environment_selected
			}
			site_name = this.new_plugin.site_name;
			should_proceed = confirm("Proceed with installing plugin " + plugin.name + " on " + site_name + "?");
			if ( ! should_proceed ) {
				return;
			}

			// Enable loading progress
			description = "Installing plugin '" +plugin.name + "' to " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"site_id": site_id, "environment": environment_selected, "description": description, "status": "queued", "command": "manage", stream: []});

			// WP ClI command to send
			wpcli = "wp plugin install " + plugin.download_link + " --force";

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_plugin.show = false
					self.snackbar.message = description
					self.snackbar.show = true
					self.new_plugin.api.items = []
					self.new_plugin.api.info = {}
					self.new_plugin.loading = false;

					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
				})
				.catch(error => {
					console.log(error.response)
					self.new_plugin.show = true
				});

		},
		uninstallPlugin ( plugin ) {
			if ( this.new_plugin.sites.length ==  1 ) {
				site_id = this.new_plugin.sites[0].site_id;
				environment_selected = this.new_plugin.sites[0].environment_selected
			} else {
				site_id = this.new_plugin.sites.map( s => s.site_id )
				environment_selected = this.new_plugin.environment_selected
			}
			site_name = this.new_plugin.site_name;
			should_proceed = confirm("Proceed with uninstalling plugin " + plugin.name + " from " + site_name + "?");
			if ( ! should_proceed ) {
				return;
			}
			// Enable loading progress
			description = "Uninstalling plugin '" +plugin.name + "' from " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"site_id": site_id, "environment": environment_selected, "description": description, "status": "queued", "command": "manage", stream: []});

			// WP ClI command to send
			wpcli = "wp plugin delete " + plugin.slug;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_plugin.show = false
					self.snackbar.message = description
					self.snackbar.show = true
					self.new_plugin.api.items = []
					self.new_plugin.api.info = {}
					self.new_plugin.loading = false;

					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
				})
				.catch(error => {
					console.log(error.response)
					self.new_plugin.show = true
				});

		},
		fetchPlugins() {
			this.new_plugin.loading = true;
			site_id = this.new_plugin.sites[0].site_id
			search = this.new_plugin.search
			var data = {
				'action': 'captaincore_ajax',
				'post_id': site_id,
				'command': "fetchPlugins",
				'page': this.new_plugin.page
			};
			if ( search ) {
				data.value = search;
			}
			self = this;
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_plugin.api.items = response.data.plugins
					self.new_plugin.api.info = response.data.info
					self.new_plugin.loading = false;
				})
				.catch(error => {
					console.log(error.response)
					self.new_plugin.loading = false;
				});
		},
		addTheme ( site_id ) {
			site = this.dialog_site.site
			this.new_theme.show = true;
			this.new_theme.sites.push( site );
			this.new_theme.site_name = site.name;
			this.new_theme.current_themes = this.dialog_site.environment_selected.themes.map( p => p.name );
			this.new_theme.environment_selected = site.environment_selected;
			this.fetchThemes();
		},
		addThemeBulk() {
			this.new_theme.show = true;
			this.new_theme.sites = this.sites_selected;
			this.new_theme.site_name = this.new_theme.sites.length + " sites";
			this.new_theme.environment_selected = this.dialog_bulk.environment_selected;
			this.fetchThemes();
		},
		installTheme ( theme ) {

			if ( this.new_theme.sites.length ==  1 ) {
				site_id = this.new_theme.sites[0].site_id;
				environment_selected = this.new_theme.sites[0].environment_selected
			} else {
				site_id = this.new_theme.sites.map( s => s.site_id )
				environment_selected = this.new_theme.environment_selected
			}

			site_name = this.new_theme.site_name;

			should_proceed = confirm("Proceed with installing theme " + theme.name + " on " + site_name + "?");

			if ( ! should_proceed ) {
				return;
			}

			// Enable loading progress
			description = "Installing theme '" + theme.name + "' to " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"site_id": site_id, "environment": environment_selected, "description": description, "status": "queued", "command": "manage", stream: []});

			// WP ClI command to send
			wpcli = "wp theme install " + theme.slug + " --force";

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_theme.show = false
					self.snackbar.message = description
					self.snackbar.show = true
					self.new_theme.api.items = []
					self.new_theme.api.info = {}
					self.new_theme.loading = false;

					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
				})
				.catch(error => {
					console.log(error.response)
					self.new_theme.show = true
				});

		},
		uninstallTheme ( theme ) {
			if ( this.new_theme.sites.length ==  1 ) {
				site_id = this.new_theme.sites[0].site_id;
				environment_selected = this.new_theme.sites[0].environment_selected
			} else {
				site_id = this.new_theme.sites.map( s => s.site_id )
				environment_selected = this.new_theme.environment_selected
			}
			site_name = this.new_theme.site_name;
			should_proceed = confirm("Proceed with uninstalling theme " + theme.name + " from " + site_name + "?");
			if ( ! should_proceed ) {
				return;
			}

			// Enable loading progress
			description = "Uninstalling theme '" + theme.name + "' from " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"site_id": site_id, "environment" : environment_selected, "description": description, "status": "queued", "command": "manage", stream: []});

			// WP ClI command to send
			wpcli = "wp theme delete " + theme.slug;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_theme.show = false
					self.snackbar.message = description
					self.snackbar.show = true
					self.new_theme.api.items = []
					self.new_theme.api.info = {}
					self.new_theme.loading = false;

					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
				})
				.catch(error => {
					console.log(error.response)
					self.new_theme.show = true
				});

		},
		fetchThemes() {
			this.new_theme.loading = true;
			site_id = this.new_theme.sites[0].site_id
			search = this.new_theme.search
			var data = {
				'action': 'captaincore_ajax',
				'post_id': site_id,
				'command': "fetchThemes",
				'page': this.new_theme.page
			};
			if ( search ) {
				data.value = search;
			}
			self = this;
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.new_theme.api.items = response.data.themes
					self.new_theme.api.info = response.data.info
					self.new_theme.loading = false;
				})
				.catch(error => {
					console.log(error.response)
					self.new_theme.loading = false;
				});
		},
		togglePlugin (plugin_name, plugin_status, site_id) {

			site = this.dialog_site.site;

			// Enable loading progress
			this.dialog_site.site.loading_plugins = true;
			site_name = this.dialog_site.site.name;

			if (plugin_status == "inactive") {
				action = "deactivate";
			}
			if (plugin_status == "active") {
				action = "activate";
			}

			description = titleCase(action) + " plugin '" + plugin_name + "' from " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id, "description": description, "status": "queued", stream: [], conn: {}});

			// WP ClI command to send
			wpcli = "wp plugin " + action + " " + plugin_name;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
				self.sites.filter(site => site.site_id == site_id)[0].loading_plugins = false;
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data )
				})
				.catch(error => {
					console.log(error.response)
			});
		},
		deletePlugin (plugin_name, site_id) {

			should_proceed = confirm("Are you sure you want to delete plugin " + plugin_name + "?");

			if ( ! should_proceed ) {
				return;
			}

			site = this.dialog_site.site;

			// Enable loading progress
			this.dialog_site.site.loading_plugins = true;

			site_name = this.dialog_site.site.name;
			description = "Delete plugin '" + plugin_name + "' from " + site_name;
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			// WP ClI command to send
			wpcli = "wp plugin delete " + plugin_name;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					environment = this.dialog_site.environment_selected
					updated_plugins = environment.plugins.filter(plugin => plugin.name != plugin_name);
					environment.plugins = updated_plugins;
					self.sites.filter(site => site.site_id == site_id)[0].loading_plugins = false;

					// Updates job id with reponsed background job id
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
			});
		},
		runUpdate( site_id ) {

			site = this.dialog_site.site;
			should_proceed = confirm("Apply all plugin/theme updates for " + site.name + "?");

			if ( ! should_proceed ) {
				return;
			}

			// New job for progress tracking
			job_id = Math.round((new Date()).getTime());
			description = "Updating themes/plugins on " + site.name;
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: [],"command":"update-wp", site_id: site.site_id});

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'environment': site.environment_selected,
				'command': "update-wp",
				'background': true
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
				});

		},
		themeAndPluginChecks( site_id ) {
			site = this.dialog_site.site;
			this.dialog_theme_and_plugin_checks.site = site;
			this.dialog_theme_and_plugin_checks.show = true;
		},
		killCommand( job_id ) {
			job = this.jobs.filter(job => job.job_id == job_id)[0]
			job.conn.send( '{ "token" : "'+ job.job_id +'", "action" : "kill" }' );
			//job.conn.close();
			job.status = "error"
		},
		runCommand( job_id ) {

			job = this.jobs.filter(job => job.job_id == job_id)[0]
			self = this;
			// console.log( "Start: select token " + job_id + " found job " + job.job_id )

			job.conn = new WebSocket( this.socket );
			job.conn.onopen = () => job.conn.send( '{ "token" : "'+ job.job_id +'", "action" : "start" }' );
			
			job.conn.onmessage = (session) => self.writeSocket( job_id, session );
			job.conn.onclose = () => {
				job = self.jobs.filter(job => job.job_id == job_id)[0]
				last_output_index = job.stream.length - 1;
				last_output = job.stream[last_output_index];

				if ( last_output == "Finished.") {
					job.status = "done"
				} else {
					job.status = "error"
				}
				
				if ( job.command == "syncSite" ) {
					self.fetchSiteInfo( job.site_id )
				}

				if ( job.command == "downloadBackup" ) {
					maybe_url = job.stream[last_output_index - 1];
					if ( maybe_url.includes("http") ) {
						window.open( maybe_url );
						self.snackbar.message = "Downloading zip."
						self.snackbar.show = true
					}
				}

				if ( job.command == "scanErrors" ) {
					self.fetchSiteInfo( job.site_id )
					self.sites.filter( s => s.site_id == job.site_id )[0].loading = false
				}

				if ( job.command == "manage" && job.environment ) {
					self.syncSite( job.site_id, job.environment );
				}

				if ( job.command == "manage" && !job.environment ) {
					self.syncSite( job.site_id );
				}

				if ( job.command == "saveUpdateSettings" ){
					// to do
				}

				if ( job.command == "update-wp" ){
					this.fetchUpdateLogs( job.site_id );
				}

				// console.log( "Done: select token " + job_id + " found job " + job.job_id )
			}
		},
		writeSocket( job_id, session ) {
			job = self.jobs.filter(job => job.job_id == job_id)[0]
			job.stream.push( session.data )
		},
		configureFathom( site_id ) {
			site = this.dialog_site.site;
			this.dialog_fathom.site = site
			this.dialog_fathom.environment = this.dialog_site.environment_selected;
			this.dialog_fathom.show = true;
		},
		configureFathomClose() {
			this.dialog_fathom.editItem = false;
			setTimeout(() => {
				this.dialog_fathom.editedItem = {}
				this.dialog_fathom.editedIndex = -1
			}, 300)
		},
		configureFathomSave() {
			if (this.dialog_fathom.editedIndex > -1) {
          		Object.assign(this.dialog_fathom.environment.fathom[this.dialog_fathom.editedIndex], this.dialog_fathom.editedItem)
			} else {
				this.dialog_fathom.environment.fathom.push(this.dialog_fathom.editedItem)
			}
			this.configureFathomClose()
		},
		newFathomItem(){
			this.dialog_fathom.environment.fathom.push({ "code": "", "domain" : "" })
		},
		deleteFathomItem (item) {
			const index = this.dialog_fathom.environment.fathom.indexOf(item)
			confirm('Are you sure you want to delete this item?') && this.dialog_fathom.environment.fathom.splice(index, 1)
		},
		saveMailgun() {
			// Prep AJAX request
			var data = {
				'action': 'captaincore_ajax',
				'post_id': this.dialog_site.site.site_id,
				'command': "updateMailgun",
				'value': this.dialog_site.site.mailgun,
			};
			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// close dialog
					this.dialog_mailgun_config.show = false;
				});
		},
		saveFathomConfigurations() {
			site = this.dialog_fathom.site;
			environment = this.dialog_fathom.environment;
			site_id = site.site_id;
			should_proceed = confirm("Apply new Fathom tracker for " + site.name + "?");

			if ( ! should_proceed ) {
				return;
			}

			// New job for progress tracking
			job_id = Math.round((new Date()).getTime());
			description = "Updating Fathom tracker on " + site.name;
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: []});

			environment.fathom.forEach( fathom => {
				fathom.domain = fathom.domain.trim()
				fathom.code = fathom.code.trim()
			})

			// Prep AJAX request
			var data = {
				'action': 'captaincore_ajax',
				'post_id': site_id,
				'command': "updateFathom",
				'environment': site.environment_selected,
				'value': environment.fathom,
			};

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					// close dialog
					this.dialog_fathom.site = {};
					this.dialog_fathom.show = false;
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
				});
		},
		updateSettings() {
			this.dialog_update_settings.show = true;
			site = this.dialog_site.site
			environment = this.dialog_site.environment_selected
			this.dialog_update_settings.environment.updates_exclude_plugins = environment.updates_exclude_plugins
			this.dialog_update_settings.environment.updates_exclude_themes = environment.updates_exclude_themes
			this.dialog_update_settings.environment.updates_enabled = environment.updates_enabled
			this.dialog_update_settings.themes = environment.themes
			this.dialog_update_settings.plugins = environment.plugins
		},
		saveUpdateSettings() {
			this.dialog_update_settings.loading = true;
			site = this.dialog_site.site

			// Adds new job
			job_id = Math.round((new Date()).getTime());
			description = "Saving update settings for " + site.name + " (" + this.dialog_site.environment_selected.environment + ")";
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: [], "command":"saveUpdateSettings"});

			// Prep AJAX request
			var data = {
				'action': 'captaincore_ajax',
				'post_id': site.site_id,
				'command': "updateSettings",
				'environment': this.dialog_site.environment_selected.environment,
				'value': { 
					"updates_exclude_plugins": this.dialog_update_settings.environment.updates_exclude_plugins, 
					"updates_exclude_themes": this.dialog_update_settings.environment.updates_exclude_themes, 
					"updates_enabled": this.dialog_update_settings.environment.updates_enabled
					}
			};

			this.dialog_update_settings.show = false;
			this.dialog_update_settings.loading = false;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					environment = this.dialog_site.environment_selected;
					environment.updates_exclude_plugins = this.dialog_update_settings.environment.updates_exclude_plugins;
					environment.updates_exclude_themes = this.dialog_update_settings.environment.updates_exclude_themes;
					environment.updates_enabled = this.dialog_update_settings.environment.updates_enabled;
					this.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					this.runCommand( response.data );
				});

		},
		deleteUserDialog( username, site_id ){
			site = this.dialog_site.site;
			environment = this.dialog_site.environment_selected;
			this.dialog_delete_user.username = username
			this.dialog_delete_user.site = site
			this.dialog_delete_user.show = true
			this.dialog_delete_user.users = environment.users.filter( u => u.user_login != username )
		},
		deleteUser() {
			if ( this.dialog_delete_user.reassign.ID == undefined ) {
				this.snackbar.message = "Can't delete user without reassign content to another user.";
				this.snackbar.show = true;
				return;
			}
			username = this.dialog_delete_user.username
			site = this.dialog_delete_user.site
			environment = this.dialog_site.environment_selected;
			should_proceed = confirm("Are you sure you want to delete user " + username + "?");

			if ( ! should_proceed ) {
				return;
			}
			site_id = site.site_id
			site_name = site.name;
			description = "Delete user '" + username + "' from " + site_name + " (" + site.environment_selected.environment + ")";
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"site_id":site_id,"command":"manage","description": description, "status": "queued", stream: []});

			// WP ClI command to send
			wpcli = "wp user delete " + username + " --reassign=" + this.dialog_delete_user.reassign.ID;

			var data = {
				'action': 'captaincore_install',
				'post_id': site_id,
				'command': "manage",
				'value': "ssh",
				'background': true,
				'environment': site.environment_selected.environment,
				'arguments': { "name":"Commands","value":"command","command":"ssh","input": wpcli }
			};

			self = this;

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					environment.users = environment.users.filter(user => user.username != username);
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.dialog_delete_user.show = false
					self.dialog_delete_user.site = {}
					self.dialog_delete_user.reassign = {}
					self.dialog_delete_user.username = ""
					self.dialog_delete_user.users = []
				});

		},
		bulkactionLaunch() {
			if ( this.dialog_bulk.environment_selected == "Production" || this.dialog_bulk.environment_selected == "Both" ) {
				this.sites_selected.forEach(site => window.open(site.environments[0].home_url));
			}
			if ( this.dialog_bulk.environment_selected == "Staging" || this.dialog_bulk.environment_selected == "Both" ) {
				this.sites_selected.forEach(site => { 
				if ( site.environments[1].home_url ) {
						window.open( site.environments[1].home_url );
				}
				});
			}
		},
		bulkactionSubmit() {
			site_ids = this.sites.filter( site => site.selected ).map( site => site.site_id );
			site_names = this.sites.filter( site => site.selected ).map( site => site.name );

			var data = {
			  'action': 'captaincore_install',
				'post_id': site_ids,
				'command': "manage",
				'background': true,
				'value': this.select_bulk_action,
				'arguments': this.select_bulk_action_arguments
		  };

			var self = this;

			description = "Running bulk " + this.select_bulk_action + " on " + site_names.join(" ");
			job_id = Math.round((new Date()).getTime());
			this.jobs.push({"job_id": job_id,"description": description, "status": "queued", stream: [], "command": "manage"});

			axios.post( ajaxurl, Qs.stringify( data ) )
				.then( response => {
					self.jobs.filter(job => job.job_id == job_id)[0].job_id = response.data;
					self.runCommand( response.data );
					self.snackbar.message = description;
					self.snackbar.show = true;
					self.dialog = false;
		  	});
		},
		filterFiles( site_id, quicksave_id ) {
			site = this.dialog_site.site;
			environment = this.dialog_site.environment_selected;
			quicksave = environment.quicksaves.filter( quicksave => quicksave.quicksave_id == quicksave_id )[0];
			search = quicksave.search;
			quicksave.filtered_files = quicksave.view_files.filter( file => file.includes( search ) );
		},
		filteredSites( value ) {
			if ( value ) {
				return true
			}
				return false
		},
		filterSites() {

			if ( this.applied_site_filter.length > 0 || this.search ) {

				search = this.search;
				filterby = this.applied_site_filter;
				filterbyversions = this.applied_site_filter_version;
				filterbystatuses = this.applied_site_filter_status;
				filter_versions = [];
				filter_statuses = [];
				versions = [];
				statuses = [];

				if ( this.applied_site_filter_version.length > 0 ) {
					// Find all themes/plugins which have selected version
					this.applied_site_filter_version.forEach(filter => {
						if(!versions.includes(filter.slug)) {
							versions.push(filter.slug);
						}
					})
					}

				if ( this.applied_site_filter_status.length > 0 ) {
					// Find all themes/plugins which have selcted version
					this.applied_site_filter_status.forEach(filter => {
						if(!statuses.includes(filter.slug)) {
							statuses.push(filter.slug);
					}
					})
				}

				if ( filterby ) {
					this.fetchFilterVersions ( filterby )
					this.fetchFilterStatus ( filterby )
					site_filters = {
						filters: this.applied_site_filter,
						versions: this.applied_site_filter_version,
						statuses: this.applied_site_filter_status,
							}
					this.fetchFilteredSites ( site_filters )
							}

				}

				// Neither filter is set so set all sites to filtered true.
				if ( this.applied_site_filter.length == 0 && !this.search ) {
					this.site_filter_status = [];
					this.site_filter_version = [];
				this.sites.forEach( s => {
					s.filtered = true
				})
				}

				this.page = 1;
		}
	}
});

</script>
<?php if ( is_plugin_active( 'arve-pro/arve-pro.php' ) ) { ?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
<script type='text/javascript' src='/wp-content/plugins/arve-pro/dist/app.js'></script>
<script type='text/javascript' src='/wp-content/plugins/advanced-responsive-video-embedder/public/arve.min.js'></script>
<?php } ?>
</body>
</html>