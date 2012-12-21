{% extends "base.html.twig" %}
{% block baseline %}
        Avenue Delleur, 8
        <br/>
        1170 Bruxelles - Belgique
        <br/>
        <span>T</span>
        +32 2 672 71 11
        <br/>
        <span>F</span>
        +32 2 672 67 17
{% endblock baseline %}

{% block social %}{% endblock social %}
     


{% block content %}
    <div id="content" class='area row-fluid'>{{content}}</div>
{% endblock content %}