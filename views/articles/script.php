<script>
  var formData = document.getElementById('form-article');
  var labelLists = document.getElementById('label-lists');
  var labels = JSON.parse('<?= json_encode($labels) ?>');

  labels = labels.map(function(row, index) {
    row.label = row.tag || row.label;
    return row;
  });

  $('[data-toggle="look-label"]').click(function(ev) {
    ev.preventDefault();
    $($(this).attr('href')).fadeToggle();
  });

  // trigger if labels textarea are typed
  formData.labels.addEventListener('keyup', function(ev) {
    // check if the target value is empty
    if (ev.target.value !== '') {
      /**
        * Get search tag data following the target value
        * @param keyword -> target value
        * @param labels -> data of labels
        */
      var searched = searchTag(ev.target.value, labels);

      /**
        * Generate label lists
        * @param labelLists -> labelLists element from @var labelLists
        * @param searched -> the result array that has been searched following the keyword at @var searched
        */
      generateLabelLists(labelLists, searched);

      // because labelLists default are display: none; this will showing the labelLists
      labelLists.style.display = 'block';

      // set the position of labelLists following the label textarea position
      labelLists.style.top =
        $(this).position().top + ev.target.clientHeight + 'px';
      labelLists.style.left = $(this).position().left + 'px';
    } else {
      // hide again the labelList
      labelLists.style.display = 'none';
    }
  });

  formData.title.addEventListener('keyup', function(ev) {
    console.log(str_slug(ev.target.value, '-'));
    formData.slug.value = str_slug(ev.target.value, '-');
  });

  /**
    * Select label function
    * by Satria AJi Putra
    * at Bandung - 30 Desember 2018
    * @param ev object
    * @param id integer
    */
  function selectLabel(ev, id, nohide) {
    ev.preventDefault();

    // get the labels selected data from label textarea inside the formData
    // and splited by comma, because the selected label was delimited by comma
    var tempLabels = formData.labels.value.split(',');

    // remove the last selected label because the last selected label is
    // the keyword of searched label
    // (get the length of temp label including the keyword and decrease with one because this is an array)
    // after that, remove that keyword with the second parameter (1)
    tempLabels.splice(tempLabels.length - 1, 1);

    // trim all spacing from the label
    tempLabels = trimLabels(tempLabels);

    // get the label by the id from the paramter
    var label = labels.filter(function(row) {
      if (row.id == id) {
        return row;
      }
    });

    // check if the id is exists
    if (label.length > 0) {
      // push the selected label to the tempLabels
      tempLabels.push(label[0].label);

      // rejoining the tempLabels by comma delimiter and adding the last comma delimiter
      formData.labels.value = tempLabels.join(', ') + ', ';
    }

    // hide the labelLists element
    // parentNode repeat third times because
    // .label-lists > ul > li > a (the a element is the ev.target)
    if (!nohide) {
      ev.target.parentNode.parentNode.parentNode.style.display = 'none';
    }
  }

  /**
    * Searching tag in an array by keyword
    * by Satria AJi Putra
    * At Bandung - 30 Desember 2018
    * @param keyword string
    * @param labels array
    * @return array
    */
  function searchTag(keyword, labels) {
    // split the keyword by comma, because the keyword from labels textarea value and commas delimited
    var keyword = keyword.split(',');

    // get the last splited array, because that is the keyword.
    // using trim because this keyword if have space value in front of them
    keyword = keyword[keyword.length - 1].trim();

    // check if the keyword is empty
    if (keyword == '') return [];

    // filter the labels array and matching the label to the keyword
    return labels.filter(function(row) {
      // matching the label to the keyword and return it
      if (row.label.toLowerCase().match(keyword)) {
        return row;
      }
    });
  }

  /**
    * Trim labels
    * this function for trimming every label from the labels textarea value
    * @param labels array
    * @return array
    */
  function trimLabels(labels) {
    var result = [];
    labels.forEach(function(row) {
      result.push(row.trim());
    });
    return result;
  }

  /**
    * Generate the label lists, this will write html script to the el
    * @param el void
    * @param labels array
    */
  function generateLabelLists(el, labels) {
    var html = '';
    labels.forEach(function(row, idx) {
      html +=
        '<li><a href="#" onclick="selectLabel(event, ' +
        row.id +
        ', false)">' +
        row.label +
        '</a></li>';
    });
    $(el).html('<ul>' + html + '</ul>');
  }

  /**
   * Parse all string to slug
   * @param title string
   * @param separator string
   * @return string
   */
  function str_slug(title, separator) {
    return title.toLowerCase().trim().split(' ').join('-');
  }

  $('#content').summernote({
    height: 400,
    fontSizes: [
      '8',
      '9',
      '10',
      '11',
      '12',
      '14',
      '18',
      '22',
      '24',
      '36',
      '48',
      '64',
      '82',
      '150'
    ]
  });
</script>