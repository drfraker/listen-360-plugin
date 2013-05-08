
<blockquote style="background-color: <?= $this->getBgStyle($review->{'recommendation-likelihood'}); ?>;" class="review">
    <p class="rating">
        Rated <?= $review->{'recommendation-likelihood'} . "/10 by " . $this->getInitials($review->{'customer-full-name'}); ?>     <span class="date">
        <?php
        echo " on ";
        $date = new DateTime((string) $review->{'completed-at'});
        echo date_format($date, 'F j, Y');
        ?>
    </span>
    </p>
    <p class="question">W|W Asked: "<?= $this->getQuestion($review->{'recommendation-likelihood'}); ?>"</p>
    <p class="comments">
        <?php
        if($review->comments != '')
        {
            echo '"'.ucfirst($review->comments).'"';
        }else
        {
            echo "No Comment Supplied.";
        }

        ?>
    </p>
    <?php if($review->{'public-response'} != ''): ?>
        <blockquote class="simple" style="width: 80%; margin-left: 15px; margin-bottom: 15px; color: #4a993e;">
            <p class="response">
                <span style="color: #666666;">Response from Wellness WORx:</span><br />
                <?= $review->{'public-response'}; ?>
            </p>
        </blockquote><!-- end response block-->
    <?php endif; ?>
</blockquote><!-- end review -->