function piechart(config) {
  const id = config.id || 'linechart'
  const dom = jQuery(`#${id}`)
  const containerW = config.width || dom.width() || 960
  const containerH = config.height || dom.height() || 500
  const margin = config.margin || { top: 20, right: 50, bottom: 30, left: 50 };
  const width = containerW - margin.left - margin.right;
  const height = containerH - margin.top - margin.bottom;

  jQuery(`#${id}`).html('')
  // .width(containerW).height(containerH)

  return window.pie = new d3pie(config.id, {
  	"header": {
  		"title": {
  			"fontSize": 24,
  			"font": "open sans"
  		},
  		"subtitle": {
  			"color": "#999999",
  			"fontSize": 12,
  			"font": "open sans"
  		},
  		"titleSubtitlePadding": 9
  	},
  	"footer": {
  		"color": "#999999",
  		"fontSize": 10,
  		"font": "open sans",
  		"location": "bottom-left"
  	},
  	"size": {
  		"canvasHeight": 280,
  		"canvasWidth": 280,
  		"pieOuterRadius": "90%"
  	},
  	"data": {
  		"sortOrder": "value-desc",
  		"content": [
  			{
  				"label": "USI",
  				"value": 218812,
  				"color": "#2383c1"
  			},
  			{
  				"label": "BTC",
  				"value": 157618,
  				"color": "#64a61f"
  			},
  			{
  				"label": "EHT",
  				"value": 95002,
  				"color": "#7b6788"
  			},
  			{
  				"label": "ZEN",
  				"value": 20000,
  				"color": "#a05c56"
  			},
  			{
  				"label": "USD",
  				"value": 50000,
  				"color": "#961919"
  			}
  		]
  	},
  	"labels": {
  		"outer": {
  			"format": "none",
  			"pieDistance": 32
  		},
  		"inner": {
  			"hideWhenLessThanPercentage": 3
  		},
  		"mainLabel": {
  			"fontSize": 11
  		},
  		"percentage": {
  			"color": "#ffffff",
  			"decimalPlaces": 0
  		},
  		"value": {
  			"color": "#adadad",
  			"fontSize": 11
  		},
  		"lines": {
  			"enabled": true
  		},
  		"truncation": {
  			"enabled": true
  		}
  	},
  	"effects": {
  		"pullOutSegmentOnClick": {
  			"speed": 400,
  			"size": 8
  		}
  	},
  	"misc": {
  		"gradient": {
  			"enabled": true,
  			"percentage": 100
  		}
  	},
  	"callbacks": {}
  })
}
