<?= "var elfinder_opt ={
						defaultView: 'list',
						useBrowserHistory: false,
						width: 800,
						lang : 'ru',
						contextmenu : {
								// navbarfolder menu
								navbar : ['open','rm', 'info'],

								// current directory menu
								cwd    : ['reload', 'back', '|', 'upload', 'mkdir', '|', 'info'],

								// current directory file menu
								files  : ['getfile', '|','open', '|', 'download', '|', 'rm', '|', 'info']
						},
                        uiOptions : {
							// toolbar configuration
							toolbar : [
								['back', 'forward'],
								['upload'],
								['open', 'download', 'getfile'],
								['info'],
								['rm'],
								['search'],
								['help']
							]
						}
					}"
?>