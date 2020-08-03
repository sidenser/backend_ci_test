var app = new Vue({
	el: '#app',
	data: {
		login: '',
		pass: '',
		post: false,
		invalidLogin: false,
		invalidPass: false,
		invalidSum: false,
		posts: [],
		addSum: 0,
		amount: 0,
		user:{
			id:null,
			personaname:null,
			wallet_balance:0,
			likes:0
		},
		likes: 0,
		commentText: '',
		packs: [
			{
				id: 1,
				price: 5
			},
			{
				id: 2,
				price: 20
			},
			{
				id: 3,
				price: 50
			},
		],
	},
	computed: {
		test: function () {
			var data = [];
			return data;
		}
	},
	created(){
		var self = this
		axios
			.get('/main_page/get_user')
			.then(function (response) {
				if(response.data.status == 'success')
				{
					self.user = response.data.user;
				}
			})

		axios
			.get('/main_page/get_all_posts')
			.then(function (response) {
				self.posts = response.data.posts;
			})
	},

	methods: {
		logout: function () {
			console.log ('logout');
		},
		logIn: function () {
			if(this.login === ''){
				this.invalidLogin = true
			}
			else if(this.pass === ''){
				this.invalidLogin = false
				this.invalidPass = true
			}
			else{
				this.invalidLogin = false
				this.invalidPass = false
				axios.post('/main_page/login', {
					login: this.login,
					password: this.pass
				})
				.then(response => {
					console.log(response, 'response');
					if(response.data.status === 'error')
					{
						this.invalidLogin = true;
						this.invalidPass = true;
					}else
					{
						this.login = this.pass = '';
						this.user = response.data.user;
						this.closeModal('#loginModal');
					}
				}).catch(error => {
					this.invalidLogin = true;
					this.invalidPass = true;
					console.error(error);
				})
			}
		},
		fiilIn: function () {
			if(this.addSum <= 0){
				this.invalidSum = true
			}
			else{
				this.invalidSum = false
				axios.post('/main_page/add_money', {
					sum: this.addSum,
				})
					.then( (response) => {
						if(response.data.status === 'error')
						{
							this.invalidSum = true;

						}else
						{
							this.invalidSum = false;
							this.user.wallet_balance = response.data.amount;
							this.addSum = null;
							this.closeModal('#addModal');
						}
					})
			}
		},
		openPost: function (id) {
			var self= this;
			axios
				.get('/main_page/get_post/' + id)
				.then(function (response) {
					self.post = response.data.post;
					if(self.post){
						setTimeout(function () {
							$('#postModal').modal('show');
						}, 500);
					}
				})
		},
		addLike: function (id) {
			//TODO
			this.likes = Math.floor(Math.random() * 100) + 1;
			return ;
			var self= this;
			axios
				.get('/main_page/like')
				.then(function (response) {
					self.likes = Math.random();
				})

		},
		buyPack: function (id) {
			//TODO можно что-то получше сделать если останется время сделаем красиво
			if(this.user.wallet_balance <= 0)
			{
				$('#noMoneyModal').modal('show');
				return ;
			}
			axios.post('/main_page/buy_boosterpack', {
				id: id,
			})
			.then( (response) => {

				if(response.data.status === 'error')
				{
					alert('Error!');
					return ;
				}else if(response.data.no_money)
				{
					$('#noMoneyModal').modal('show');
					return ;
				}

				if(response.data.amount !== 0){
					this.amount = response.data.amount;
					setTimeout(function () {
						$('#amountModal').modal('show');
					}, 500);
				}
			})
		},
		closeModal: function(selector)
		{
			console.log('selector', selector);
			setTimeout(()=> {
				$(selector).modal('hide');
			}, 500);
		},
	}
});

